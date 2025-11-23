<?= $this->extend('templates/template'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-semibold">Data Nilai Siswa</h5>
            <a href="<?= base_url('admin/nilai/export') ?>" class="btn btn-success">Export Excel</a>
        </div>

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">No</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Nama Siswa</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Username</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Total Nilai</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Tanggal Tes</h6></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach($nilai as $n): ?>
                    <tr>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0"><?= $i++ ?></h6></td>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-1"><?= $n['nama_siswa'] ?></h6></td>
                        <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= $n['username'] ?></p></td>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-1"><?= $n['total_nilai'] ?></h6></td>
                        <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= $n['created_at'] ?></p></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
