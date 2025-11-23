<?= $this->extend('templates/template'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Tambah Soal</h5>
        
        <?php if(session()->getFlashdata('errors')):?>
            <div class="alert alert-danger">
                <ul>
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif;?>

        <form action="<?= base_url('admin/soal/store') ?>" method="post">

            <div class="mb-3">
                <label for="soal" class="form-label">Pertanyaan</label>
                <textarea class="form-control" name="soal" rows="3" required><?= old('soal') ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pilihan_a" class="form-label">Pilihan A</label>
                    <input type="text" class="form-control" name="pilihan_a" value="<?= old('pilihan_a') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pilihan_b" class="form-label">Pilihan B</label>
                    <input type="text" class="form-control" name="pilihan_b" value="<?= old('pilihan_b') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pilihan_c" class="form-label">Pilihan C</label>
                    <input type="text" class="form-control" name="pilihan_c" value="<?= old('pilihan_c') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pilihan_d" class="form-label">Pilihan D</label>
                    <input type="text" class="form-control" name="pilihan_d" value="<?= old('pilihan_d') ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="jawaban" class="form-label">Kunci Jawaban</label>
                <select class="form-select" name="jawaban" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="bobot_nilai" class="form-label">Bobot Nilai</label>
                <input type="number" class="form-control" name="bobot_nilai" value="10" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= base_url('admin/soal') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>
