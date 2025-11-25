<?= $this->extend('templates/template'); ?>

<?= $this->section('content'); ?>
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
                        <strong>Format Excel yang diharapkan:</strong>
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
