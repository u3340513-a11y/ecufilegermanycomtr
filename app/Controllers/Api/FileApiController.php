<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use Core\Controller;
use Core\Request;
use Core\Session;
use App\Helpers\FileUploader;
use App\Repositories\FileRepository;

final class FileApiController extends Controller
{
    public function upload(Request $request): void
    {
        if (!$request->hasFile('file')) {
            $this->json(['success' => false, 'message' => 'Dosya seçilmedi.'], 422);
        }

        $uploader = new FileUploader('requests');

        try {
            $result = $uploader->upload($request->file('file'));

            $uploaded = Session::get('uploaded_files', []);
            $uploaded[] = $result;
            Session::set('uploaded_files', $uploaded);

            $this->json([
                'success'  => true,
                'filename' => $result['filename'],
                'original' => $result['original_name'],
                'size'     => $result['size'],
            ]);
        } catch (\Throwable $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function delete(Request $request): void
    {
        $filename = $request->post('filename');
        $uploaded = Session::get('uploaded_files', []);

        $uploaded = array_filter($uploaded, function ($file) use ($filename) {
            if ($file['filename'] === $filename) {
                @unlink($file['path']);
                return false;
            }
            return true;
        });

        Session::set('uploaded_files', array_values($uploaded));
        $this->json(['success' => true]);
    }

    public function download(Request $request, string $id): void
    {
        $fileRepo = new FileRepository();
        $file = $fileRepo->findById((int) $id);

        if (!$file) {
            $this->response->notFound();
        }

        $reqRepo = new \App\Repositories\RequestRepository();
        $req = $reqRepo->findById((int) $file['request_id']);

        if (!$req || ((int) $req['user_id'] !== $this->userId() && !Session::isAdmin())) {
            $this->response->forbidden();
        }

        $this->response->download($file['path'], $file['original_name']);
    }
}
