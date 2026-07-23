<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Request;
use Core\Session;
use App\Services\AuthService;

final class EmailVerificationController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    public function verify(Request $request, string $token): void
    {
        if ($this->authService->verifyEmail($token)) {
            $this->view('auth.email-verified', [], 'auth');
            return;
        }

        $this->withError('Geçersiz veya süresi dolmuş doğrulama bağlantısı.', '/login');
    }

    public function resend(Request $request): void
    {
        $userId = Session::userId();
        if ($userId && $this->authService->resendVerification($userId)) {
            $this->withSuccess('Doğrulama e-postası tekrar gönderildi.', '/login');
        }

        $this->withError('Doğrulama e-postası gönderilemedi.', '/login');
    }
}
