<?php

declare(strict_types=1);

namespace Core;

final class Response
{
    public function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function redirect(string $url, int $status = 302): never
    {
        http_response_code($status);
        header("Location: {$url}");
        exit;
    }

    public function back(): never
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    public function setStatus(int $code): self
    {
        http_response_code($code);
        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        header("{$name}: {$value}");
        return $this;
    }

    public function download(string $filePath, string $fileName): never
    {
        if (!file_exists($filePath)) {
            http_response_code(404);
            exit('Dosya bulunamadı.');
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($filePath);
        exit;
    }

    public function notFound(): never
    {
        http_response_code(404);
        View::render('errors/404');
        exit;
    }

    public function forbidden(): never
    {
        http_response_code(403);
        View::render('errors/403');
        exit;
    }

    public function error(string $message = 'Sunucu hatası', int $code = 500): never
    {
        http_response_code($code);
        View::render('errors/500', ['message' => $message]);
        exit;
    }
}
