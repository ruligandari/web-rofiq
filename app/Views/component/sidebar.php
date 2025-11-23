<!-- sweetalert -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// Get current URL segment to determine active menu
$uri = service('uri');
$segment2 = $uri->getSegment(2); // admin/[segment2]/...
?>

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('admin/dashboard') ?>" class="text-nowrap logo-img text-center d-block py-2 w-100">
                <img src="<?= base_url() ?>/assets/images/logos/logo_smk.png" width="120" alt="" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item <?= ($segment2 == 'dashboard') ? 'active' : '' ?>">
                    <a class="sidebar-link <?= ($segment2 == 'dashboard') ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Tools</span>
                </li>
                <li class="sidebar-item <?= ($segment2 == 'siswa') ? 'active' : '' ?>">
                    <a class="sidebar-link <?= ($segment2 == 'siswa') ? 'active' : '' ?>" href="<?= base_url('admin/siswa') ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-user"></i>
                        </span>
                        <span class="hide-menu">Siswa</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($segment2 == 'soal') ? 'active' : '' ?>">
                    <a class="sidebar-link <?= ($segment2 == 'soal') ? 'active' : '' ?>" href="<?= base_url('admin/soal') ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-article"></i>
                        </span>
                        <span class="hide-menu">Soal</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($segment2 == 'nilai') ? 'active' : '' ?>">
                    <a class="sidebar-link <?= ($segment2 == 'nilai') ? 'active' : '' ?>" href="<?= base_url('admin/nilai') ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-report"></i>
                        </span>
                        <span class="hide-menu">Nilai</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Logout</span>
                </li>

                <li class="sidebar-item">
                    <!-- ada konfirmasi sebelum logout dengan sweetalert -->
                    <a class="sidebar-link" href="#" id="btnLogout" aria-expanded="false">
                        <span>
                            <i class="ti ti-logout"></i>
                        </span>
                        <span class="hide-menu">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    <script>
        document.getElementById('btnLogout').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default dari link
            Swal.fire({
                title: 'Yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user mengkonfirmasi, redirect ke halaman logout
                    window.location.href = "<?= base_url('admin/logout') ?>";
                }
            });
        });
    </script>
</aside>
