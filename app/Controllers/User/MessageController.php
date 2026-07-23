<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Core\Controller;
use Core\Request;
use App\Repositories\MessageRepository;
use App\Repositories\RequestRepository;
use App\Services\NotificationService;
use App\Helpers\FileUploader;

final class MessageController extends Controller
{
    public function send(Request $request): void
    {
        $requestId = (int) $request->post('request_id');
        $content = trim($request->post('content', ''));

        $reqRepo = new RequestRepository();
        $reqDetail = $reqRepo->findById($requestId);

        if (!$reqDetail || (int) $reqDetail['user_id'] !== $this->userId()) {
            $this->json(['success' => false, 'message' => 'Yetkisiz erişim.'], 403);
        }

        if ($content === '' && !$request->hasFile('attachment')) {
            $this->json(['success' => false, 'message' => 'Mesaj veya dosya gerekli.'], 422);
        }

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $uploader = new FileUploader('messages');
            try {
                $file = $uploader->upload($request->file('attachment'));
                $attachmentPath = $file['path'];
                $attachmentName = $file['original_name'];
            } catch (\Throwable $e) {
                $this->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
        }

        $msgRepo = new MessageRepository();
        $msgRepo->create([
            'request_id'      => $requestId,
            'user_id'         => $this->userId(),
            'content'         => $content,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_admin'        => 0,
            'is_read'         => 0,
        ]);

        $admins = \Core\Database::getInstance()->fetchAll("SELECT id FROM users WHERE role = 'admin'");
        $notifService = new NotificationService();
        foreach ($admins as $admin) {
            $notifService->notifyNewMessage((int) $admin['id'], $reqDetail['ticket_no']);
        }

        $this->json(['success' => true, 'message' => 'Mesaj gönderildi.']);
    }
}
