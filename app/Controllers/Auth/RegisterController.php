<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Request;
use Core\Session;
use App\Services\AuthService;
use App\Helpers\Validator;

final class RegisterController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    public function showRegister(Request $request): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/register', [], 'auth');
    }

    public function register(Request $request): void
    {
        $validator = new Validator($request->all());
        $validator->required('name', 'Ad Soyad')->min('name', 2, 'Ad Soyad')->max('name', 100, 'Ad Soyad')
                  ->required('email', 'E-posta')->email('email')->unique('email', 'users', 'email', null, 'E-posta')
                  ->required('password', 'Şifre')->min('password', 8, 'Şifre')
                  ->confirmed('password', 'password_confirmation', 'Şifre');

        if ($validator->fails()) {
            $this->withErrors($validator->errors(), '/register');
        }

        $this->authService->register($request->only(['name', 'email', 'password', 'phone', 'company']));

        Session::flash('success', 'Kayıt başarılı. E-posta adresinize doğrulama linki gönderildi.');
        $this->redirect('/login');
    }
}
