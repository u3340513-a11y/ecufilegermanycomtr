<?php $pageTitle = 'Profil'; $currentPage = 'profile'; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="profile-avatar mb-3">
                    <?php if ($user['avatar']): ?>
                        <img src="<?= \Core\App::url('storage/uploads/avatars/' . $user['avatar']) ?>" alt="Avatar" class="rounded-circle" width="120" height="120">
                    <?php else: ?>
                        <div class="avatar-placeholder avatar-placeholder--lg"><?= mb_substr($user['name'], 0, 1) ?></div>
                    <?php endif; ?>
                </div>
                <h5 class="mb-1"><?= \Core\View::escape($user['name']) ?></h5>
                <p class="text-muted mb-3"><?= \Core\View::escape($user['email']) ?></p>

                <form method="POST" action="/dashboard/profile/avatar" enctype="multipart/form-data">
                    <?= \Core\View::csrf() ?>
                    <div class="mb-2">
                        <input type="file" class="form-control form-control-sm" name="avatar" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Avatar Güncelle</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h6 class="mb-0">Hesap Bilgileri</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Kredi Bakiyesi</span>
                    <span class="fw-600"><?= $user['credit_balance'] ?> Kr</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Üyelik Tarihi</span>
                    <span><?= date('d.m.Y', strtotime($user['created_at'])) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">E-posta Doğrulama</span>
                    <span class="badge bg-<?= $user['email_verified'] ? 'success' : 'warning' ?>">
                        <?= $user['email_verified'] ? 'Doğrulanmış' : 'Bekliyor' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Kişisel Bilgiler</h6></div>
            <div class="card-body">
                <form method="POST" action="/dashboard/profile">
                    <?= \Core\View::csrf() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Ad Soyad</label>
                            <input type="text" class="form-control <?= \Core\View::hasError('name') ? 'is-invalid' : '' ?>"
                                   id="name" name="name" value="<?= \Core\View::escape($user['name']) ?>" required>
                            <?= \Core\View::error('name') ?>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-posta</label>
                            <input type="email" class="form-control <?= \Core\View::hasError('email') ? 'is-invalid' : '' ?>"
                                   id="email" name="email" value="<?= \Core\View::escape($user['email']) ?>" required>
                            <?= \Core\View::error('email') ?>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telefon</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= \Core\View::escape($user['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="company" class="form-label">Firma</label>
                            <input type="text" class="form-control" id="company" name="company" value="<?= \Core\View::escape($user['company'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Kaydet</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="mb-0">Şifre Değiştir</h6></div>
            <div class="card-body">
                <form method="POST" action="/dashboard/profile/password">
                    <?= \Core\View::csrf() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="current_password" class="form-label">Mevcut Şifre</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="new_password" class="form-label">Yeni Şifre</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        </div>
                        <div class="col-md-6">
                            <label for="new_password_confirmation" class="form-label">Yeni Şifre Tekrar</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-key me-2"></i>Şifreyi Değiştir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
