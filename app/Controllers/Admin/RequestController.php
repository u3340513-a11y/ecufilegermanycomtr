<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use App\Services\RequestService;
use App\Repositories\RequestRepository;
use App\Repositories\MessageRepository;
use App\Repositories\FileRepository;
use App\Services\NotificationService;
use App\Helpers\FileUploader;

final class RequestController extends Controller
{
    private RequestRepository $requestRepo;
    private RequestService $requestService;

    public function __construct()
    {
        parent::__construct();
        $this->requestRepo = new RequestRepository();
        $this->requestService = new RequestService();
    }

    public function index(Request $request): void
    {
        $page = (int) ($request->get('page') ?: 1);
        $status = $request->get('status');
        $result = $this->requestRepo->getAll($page, 20, $status);
        $statusCounts = $this->requestRepo->countByStatus();

        $this->view('admin/requests/index', [
            'pageTitle'    => 'Talepler',
            'currentPage'  => 'admin-requests',
            'requests'     => $result['data'],
            'total'        => $result['total'],
            'page'         => $result['page'],
            'totalPages'   => $result['totalPages'],
            'statusFilter' => $status,
            'statusCounts' => $statusCounts,
        ], 'admin');
    }

    public function show(Request $request, string $id): void
    {
        $detail = $this->requestService->getRequestDetail((int) $id);
        if (!$detail) { $this->redirect('/admin/requests'); }

        $msgRepo = new MessageRepository();
        $messages = $msgRepo->getByRequest((int) $id);

        $db = \Core\Database::getInstance();
        $allPackages = $db->fetchAll(
            'SELECT * FROM service_packages WHERE is_active = 1 ORDER BY sort_order ASC'
        );

        $this->view('admin/requests/show', [
            'pageTitle'   => 'Talep #' . $detail['ticket_no'],
            'currentPage' => 'admin-requests',
            'req'         => $detail,
            'messages'    => $messages,
            'allPackages' => $allPackages,
        ], 'admin');
    }

    public function updateStatus(Request $request, string $id): void
    {
        $status = $request->post('status');
        $validStatuses = ['pending', 'reviewing', 'processing', 'revision', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses, true)) {
            $this->withError('Geçersiz durum.', '/admin/requests/' . $id);
        }

        $this->requestService->updateStatus((int) $id, $status, $this->userId());
        $this->withSuccess('Talep durumu güncellendi.', '/admin/requests/' . $id);
    }

    public function uploadFile(Request $request, string $id): void
    {
        if (!$request->hasFile('file')) {
            $this->withError('Dosya seçilmedi.', '/admin/requests/' . $id);
        }

        $type = $request->post('file_type', 'revision');
        $uploader = new FileUploader('requests');

        try {
            $file = $uploader->upload($request->file('file'));
            $fileRepo = new FileRepository();
            $version = $fileRepo->getNextVersion((int) $id, $type);

            $fileRepo->create([
                'request_id'    => (int) $id,
                'user_id'       => $this->userId(),
                'filename'      => $file['filename'],
                'original_name' => $file['original_name'],
                'path'          => $file['path'],
                'size'          => $file['size'],
                'mime_type'     => $file['mime_type'],
                'type'          => $type,
                'version'       => $version,
            ]);

            $reqDetail = $this->requestRepo->findById((int) $id);
            if ($reqDetail) {
                $notifService = new NotificationService();
                $notifService->notifyRequestUpdate((int) $reqDetail['user_id'], $reqDetail['ticket_no'], $reqDetail['status']);
            }

            $this->withSuccess('Dosya yüklendi.', '/admin/requests/' . $id);
        } catch (\Throwable $e) {
            $this->withError($e->getMessage(), '/admin/requests/' . $id);
        }
    }

    public function sendMessage(Request $request, string $id): void
    {
        $content = trim($request->post('content', ''));
        if ($content === '') {
            $this->withError('Mesaj boş olamaz.', '/admin/requests/' . $id);
        }

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $uploader = new FileUploader('messages');
            $file = $uploader->upload($request->file('attachment'));
            $attachmentPath = $file['path'];
            $attachmentName = $file['original_name'];
        }

        $msgRepo = new MessageRepository();
        $msgRepo->create([
            'request_id'      => (int) $id,
            'user_id'         => $this->userId(),
            'content'         => $content,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_admin'        => 1,
            'is_read'         => 0,
        ]);

        $reqDetail = $this->requestRepo->findById((int) $id);
        if ($reqDetail) {
            $notifService = new NotificationService();
            $notifService->notifyNewMessage((int) $reqDetail['user_id'], $reqDetail['ticket_no']);
        }

        $this->withSuccess('Mesaj gönderildi.', '/admin/requests/' . $id);
    }

    public function addService(Request $request, string $id): void
    {
        $servicePackageId = (int) $request->post('service_package_id');
        if ($servicePackageId <= 0) {
            $this->withError('Geçersiz servis seçimi.', '/admin/requests/' . $id);
        }

        try {
            $result = $this->requestService->addServiceToRequest((int) $id, $servicePackageId, $this->userId());
            $this->withSuccess(
                "✅ \"{$result['service_name']}\" talebi eklendi. Tahsil edilen: {$result['credit_cost']} kredi. Yeni toplam: {$result['new_total']} kredi.",
                '/admin/requests/' . $id
            );
        } catch (\Throwable $e) {
            $this->withError($e->getMessage(), '/admin/requests/' . $id);
        }
    }
}
