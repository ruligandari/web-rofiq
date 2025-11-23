<?= $this->extend('templates/template'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-semibold">Data Siswa</h5>
            <div>
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="ti ti-file-import"></i> Import Excel
                </button>
                <a href="<?= base_url('admin/siswa/create') ?>" class="btn btn-primary">Tambah Siswa</a>
                <a href="<?= base_url('admin/siswa/export') ?>" class="btn btn-secondary">Export Excel</a>
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
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/siswa/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <strong>Format Excel yang diharapkan:</strong>
                            <a href="<?= base_url('admin/siswa/download-template') ?>" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-download"></i> Download Template
                            </a>
                        </div>
                        <ul class="mb-0 mt-2">
                            <li>Kolom A: Nama Lengkap</li>
                            <li>Kolom B: Username (Unik)</li>
                            <li>Kolom C: Password (Min 6 karakter)</li>
                        </ul>
                        <small class="text-muted">Baris pertama akan diabaikan (header)</small>
                    </div>

                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xls,.xlsx" required>
                        <small class="text-muted">Format: .xls atau .xlsx</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
