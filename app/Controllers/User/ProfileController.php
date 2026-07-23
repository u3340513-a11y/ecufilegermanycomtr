<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Core\Controller;
use Core\Request;
use Core\Session;
use App\Repositories\UserRepository;
use App\Helpers\Validator;
use App\Helpers\FileUploader;

final class ProfileController extends Controller
{
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
    }

    public function index(Request $request): void
    {
        $user = $this->userRepo->findById($this->userId());
        $this->view('user/profile', [
            'pageTitle'   => 'Profil',
            'currentPage' => 'profile',
            'user'        => $user,
        ]);
    }

    public function update(Request $request): void
    {
        $validator = new Validator($request->all());
        $validator->required('name', 'Ad Soyad')->min('name', 2, 'Ad Soyad')
                  ->required('email', 'E-posta')->email('email')
                  ->unique('email', 'users', 'email', $this->userId(), 'E-posta');

        if ($validator->fails()) {
            $this->withErrors($validator->errors(), '/dashboard/profile');
        }

        $this->userRepo->update($this->userId(), [
            'name'    => $request->post('name'),
            'email'   => $request->post('email'),
            'phone'   => $request->post('phone'),
            'company' => $request->post('company'),
        ]);

        Session::set('user_name', $request->post('name'));
        $this->withSuccess('Profil güncellendi.', '/dashboard/profile');
    }

    public function updateAvatar(Request $request): void
    {
        if (!$request->hasFile('avatar')) {
            $this->withError('Dosya seçilmedi.', '/dashboard/profile');
        }

        $uploader = new FileUploader('avatars');
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png', 'webp']);
        $uploader->setMaxSize(2 * 1024 * 1024);

        try {
            $file = $uploader->upload($request->file('avatar'));

            $user = $this->userRepo->findById($this->userId());
            if ($user['avatar']) {
                $uploader->delete(BASE_PATH . '/storage/uploads/avatars/' . $user['avatar']);
            }

            $this->userRepo->update($this->userId(), ['avatar' => $file['filename']]);
            $this->withSuccess('Avatar güncellendi.', '/dashboard/profile');
        } catch (\Throwable $e) {
            $this->withError($e->getMessage(), '/dashboard/profile');
        }
    }

    public function updatePassword(Request $request): void
    {
        $validator = new Validator($request->all());
        $validator->required('current_password', 'Mevcut Şifre')
                  ->required('new_password', 'Yeni Şifre')->min('new_password', 8, 'Yeni Şifre')
                  ->confirmed('new_password', 'new_password_confirmation', 'Yeni Şifre');

        if ($validator->fails()) {
            $this->withErrors($validator->errors(), '/dashboard/profile');
        }

        $user = $this->userRepo->findById($this->userId());
        if (!password_verify($request->post('current_password'), $user['password'])) {
            $this->withError('Mevcut şifre hatalı.', '/dashboard/profile');
        }

        $this->userRepo->updatePassword($this->userId(), $request->post('new_password'));
        $this->withSuccess('Şifre başarıyla değiştirildi.', '/dashboard/profile');
    }
}
