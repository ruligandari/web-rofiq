<?= $this->extend('templates/template'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-semibold">Data Soal</h5>
            <div class="d-flex align-items-center gap-2">
                <form action="<?= base_url('admin/soal/settings') ?>" method="post" class="d-flex align-items-center gap-2">
                    <label for="quiz_question_count" class="form-label mb-0 text-nowrap">Jumlah Soal Tampil:</label>
                    <input type="number" name="quiz_question_count" id="quiz_question_count" class="form-control form-control-sm" style="width: 70px;" value="<?= $quiz_question_count ?? 10 ?>" min="1">
                    <button type="submit" class="btn btn-sm btn-info" title="Simpan Pengaturan">
                        <i class="ti ti-device-floppy"></i>
                    </button>
                </form>
                <div class="vr mx-2"></div>
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="ti ti-file-import"></i> Import Excel
                </button>
                <a href="<?= base_url('admin/soal/create') ?>" class="btn btn-primary">Tambah Soal</a>
            </div>
        </div>



        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">No</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Soal</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">A</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">B</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">C</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">D</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Jawaban</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Bobot</h6></th>
                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Aksi</h6></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach($soal as $s): ?>
                    <tr>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0"><?= $i++ ?></h6></td>
                        <td class="border-bottom-0">
                            <p class="mb-0 fw-normal text-wrap" style="max-width: 300px;"><?= substr(strip_tags($s['soal']), 0, 100) ?>...</p>
                        </td>
                        <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= $s['pilihan_a'] ?></p></td>
                        <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= $s['pilihan_b'] ?></p></td>
                        <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= $s['pilihan_c'] ?></p></td>
                        <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= $s['pilihan_d'] ?></p></td>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-1"><?= $s['jawaban'] ?></h6></td>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-1"><?= $s['bobot_nilai'] ?></h6></td>
                        <td class="border-bottom-0">
                            <a href="<?= base_url('admin/soal/edit/'.$s['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="#" onclick="deleteConfirm('<?= base_url('admin/soal/delete/'.$s['id']) ?>'); return false;" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Soal dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/soal/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <strong>Format Excel yang diharapkan:</strong>
                            <a href="<?= base_url('admin/soal/download-template') ?>" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-download"></i> Download Template
                            </a>
                        </div>
                        <ul class="mb-0 mt-2">
                            <li>Kolom A: Soal</li>
                            <li>Kolom B: Pilihan A</li>
                            <li>Kolom C: Pilihan B</li>
                            <li>Kolom D: Pilihan C</li>
                            <li>Kolom E: Pilihan D</li>
                            <li>Kolom F: Jawaban (A/B/C/D)</li>
                            <li>Kolom G: Bobot Nilai (opsional, default: 10)</li>
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
