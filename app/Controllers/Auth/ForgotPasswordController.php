<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Request;
use App\Services\AuthService;
use App\Helpers\Validator;

final class ForgotPasswordController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    public function showForm(Request $request): void
    {
        $this->view('auth/forgot-password', [], 'auth');
    }

    public function sendLink(Request $request): void
    {
        $validator = new Validator($request->all());
        $validator->required('email', 'E-posta')->email('email');

        if ($validator->fails()) {
            $this->withErrors($validator->errors(), '/forgot-password');
        }

        $this->authService->sendResetLink($request->post('email'));

        $this->withSuccess(
            'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.',
            '/forgot-password'
        );
    }

    public function showReset(Request $request, string $token): void
    {
        $this->view('auth/reset-password', ['token' => $token], 'auth');
    }

    public function reset(Request $request): void
    {
        $validator = new Validator($request->all());
        $validator->required('token', 'Token')
                  ->required('password', 'Yeni Şifre')->min('password', 8, 'Yeni Şifre')
                  ->confirmed('password', 'password_confirmation', 'Şifre');

        if ($validator->fails()) {
            $this->withErrors($validator->errors(), '/forgot-password');
        }

        if ($this->authService->resetPassword($request->post('token'), $request->post('password'))) {
            $this->withSuccess('Şifreniz başarıyla değiştirildi.', '/login');
        }

        $this->withError('Geçersiz veya süresi dolmuş bağlantı.', '/forgot-password');
    }
}
