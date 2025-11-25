<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Admin Panel' ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url() ?>/assets/images/logos/logo_smk.png" />
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
    <?= $this->renderSection('styles'); ?>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?= $this->include('component/sidebar') ?>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <?= $this->include('component/header') ?>
            <!--  Header End -->
            <div class="container-fluid">
                <!--  Row 1 -->
                <?= $this->renderSection('content'); ?>
                <!--  Row 1 End -->
            </div>
        </div>
    </div>
    <script src="<?= base_url() ?>/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/sidebarmenu.js"></script>
    <script src="<?= base_url() ?>/assets/js/app.min.js"></script>
    <script src="<?= base_url() ?>/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="<?= base_url() ?>/assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="<?= base_url() ?>/assets/js/dashboard.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('.table').DataTable();

            // SweetAlert for Success
            <?php if (session()->getFlashdata('success')) : ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?= session()->getFlashdata('success') ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            <?php endif; ?>

            // SweetAlert for Error
            <?php if (session()->getFlashdata('error')) : ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '<?= session()->getFlashdata('error') ?>',
                });
            <?php endif; ?>

            // SweetAlert for Validation Errors
            <?php if (session()->getFlashdata('errors')) : ?>
                <?php 
                    $errors = session()->getFlashdata('errors');
                    $error_list = '';
                    if(is_array($errors)) {
                        foreach($errors as $error) {
                            $error_list .= $error . '<br>';
                        }
                    } else {
                        $error_list = $errors;
                    }
                ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    html: '<?= $error_list ?>',
                });
            <?php endif; ?>
        });

        function deleteConfirm(url) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
    </script>
    <?= $this->renderSection('script'); ?>
</body>

</html>
