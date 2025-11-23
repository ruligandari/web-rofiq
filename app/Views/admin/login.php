<?= $this->extend('templates/auth_template'); ?>

<?= $this->section('login'); ?>
<?php if(session()->getFlashdata('msg')):?>
    <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
<?php endif;?>
<form action="/admin/login" method="post">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
    </div>
    <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
</form>
<?= $this->endSection(); ?>
