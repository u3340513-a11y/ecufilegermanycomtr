<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Request;
use Core\Session;
use App\Services\AuthService;
use App\Helpers\Validator;

final class LoginController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    public function showLogin(Request $request): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect(Session::isAdmin() ? '/admin' : '/dashboard');
        }
        $this->view('auth/login', [], 'auth');
    }

    public function login(Request $request): void
    {
        $validator = new Validator($request->all());
        $validator->required('email', 'E-posta')->email('email')
                  ->required('password', 'Şifre');

        if ($validator->fails()) {
            $this->withErrors($validator->errors(), '/login');
        }

        if ($this->authService->login($request->post('email'), $request->post('password'))) {
            Session::flash('success', 'Giriş başarılı.');
            $this->redirect(Session::isAdmin() ? '/admin' : '/dashboard');
        }

        Session::flash('old_input', ['email' => $request->post('email')]);
        if (!Session::hasFlash('error')) {
            Session::flash('error', 'E-posta veya şifre hatalı.');
        }
        $this->redirect('/login');
    }

    public function logout(Request $request): void
    {
        $this->authService->logout();
        Session::flash('success', 'Başarıyla çıkış yapıldı.');
        $this->redirect('/login');
    }
}
