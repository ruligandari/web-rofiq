<?= $this->extend('templates/template'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-semibold">Data Siswa</h5>
            <div>
                <a href="<?= base_url('admin/siswa/create') ?>" class="btn btn-primary">Tambah Siswa</a>
                <a href="<?= base_url('admin/siswa/export') ?>" class="btn btn-success">Export Excel</a>
            </div>
        </div>

        <?php if(session()->getFlashdata('success')):?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif;?>

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">No</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Nama</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Username</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Aksi</h6>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach($siswa as $s): ?>
                    <tr>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0"><?= $i++ ?></h6></td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-1"><?= $s['nama'] ?></h6>
                        </td>
                        <td class="border-bottom-0">
                            <p class="mb-0 fw-normal"><?= $s['username'] ?></p>
                        </td>
                        <td class="border-bottom-0">
                            <a href="<?= base_url('admin/siswa/edit/'.$s['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="#" onclick="deleteConfirm('<?= base_url('admin/siswa/delete/'.$s['id']) ?>'); return false;" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
