<?php $pageTitle = 'Kullanıcı Düzenle'; $currentPage = 'admin-users'; ?>
<div class="card"><div class="card-body">
    <form method="POST" action="/admin/users/<?= $user['id'] ?>/update">
        <?= \Core\View::csrf() ?>
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Ad Soyad</label><input type="text" class="form-control" name="name" value="<?= \Core\View::escape($user['name']) ?>" required></div>
            <div class="col-md-6"><label class="form-label">E-posta</label><input type="email" class="form-control" name="email" value="<?= \Core\View::escape($user['email']) ?>" required></div>
            <div class="col-md-6"><label class="form-label">Telefon</label><input type="text" class="form-control" name="phone" value="<?= \Core\View::escape($user['phone'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Firma</label><input type="text" class="form-control" name="company" value="<?= \Core\View::escape($user['company'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Rol</label><select class="form-select" name="role"><option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Kullanıcı</option><option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option></select></div>
        </div>
        <div class="mt-3"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Kaydet</button> <a href="/admin/users/<?= $user['id'] ?>" class="btn btn-outline-secondary">İptal</a></div>
    </form>
</div></div>
