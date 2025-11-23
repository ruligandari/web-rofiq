<?= $this->extend('templates/template'); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Dashboard</h5>
                <p class="mb-4">Welcome back, <?= session()->get('username') ?>!</p>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <a href="<?= base_url('admin/siswa') ?>" class="text-decoration-none">
                            <div class="card border-start border-primary border-4 hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="ti ti-users fs-8 text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-muted">Total Siswa</h6>
                                                <h3 class="mb-0 fw-bold text-dark"><?= $total_siswa ?></h3>
                                            </div>
                                        </div>
                                        <div>
                                            <i class="ti ti-arrow-right fs-6 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('admin/soal') ?>" class="text-decoration-none">
                            <div class="card border-start border-success border-4 hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="ti ti-file-text fs-8 text-success"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-muted">Total Soal</h6>
                                                <h3 class="mb-0 fw-bold text-dark"><?= $total_soal ?></h3>
                                            </div>
                                        </div>
                                        <div>
                                            <i class="ti ti-arrow-right fs-6 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('admin/nilai') ?>" class="text-decoration-none">
                            <div class="card border-start border-warning border-4 hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="ti ti-chart-bar fs-8 text-warning"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-muted">Total Nilai</h6>
                                                <h3 class="mb-0 fw-bold text-dark"><?= $total_nilai ?></h3>
                                            </div>
                                        </div>
                                        <div>
                                            <i class="ti ti-arrow-right fs-6 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold mb-4">Top 10 Nilai Siswa</h5>
                                <canvas id="studentGradesChart" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Student Grades Chart
    const ctx = document.getElementById('studentGradesChart').getContext('2d');
    const studentGradesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php foreach($student_grades as $grade): ?>
                    '<?= $grade['nama'] ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                label: 'Nilai',
                data: [
                    <?php foreach($student_grades as $grade): ?>
                        <?= $grade['nilai'] ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 10
                    },
                    title: {
                        display: true,
                        text: 'Nilai'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Siswa'
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection(); ?>
