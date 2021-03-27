<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="O SFGA é um sistema de gestão de furos de água privados, com este sistema é ...">
    <meta name="keywords" content="SGFA,Sistema de gestao de furo de agua,nextgen it">
    <meta name="author" content="NextGeneration IT">

    <title>AGUAS AZ | Sistema de Gestão de Furo de Água</title>

    <!-- Custom fonts for this template-->
    <link href="<?= assets('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= assets('css/sb-admin-2.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= assets('css/costum.css') ?>" rel="stylesheet" type="text/css">
    <script src="<?= assets('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand mt-4 d-flex align-items-center justify-content-center" href="<?= $router->route("admin.home") ?>">
            <div class="sidebar-brand-text">
                <img src="<?=assets("img/SGFA.png")?>" height="120px" width="200px">
            </div>
        </a>

        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("user.home") ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Painel Inicial</span></a>
        </li>

        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("user.facturas") ?>">
                <i class="fas fa-fw fa-file-invoice-dollar"></i>
                <span>Facturas</span></a>
        </li>

        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("user.recibos") ?>">
                <i class="fas fa-fw fa-receipt"></i>
                <span>Recibos</span></a>
        </li>

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 text-uppercase small"><?= mb_split(" ",$cliente->Nome)[0]; ?></span>
                            <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Meus Dados
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= $router->route("auth.logout") ?>">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Sair
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <main>
                <div class="container-fluid">

                    <div id="ajaxloader" class="loader" hidden></div>
                    <?= $this->section('content') ?>

                </div>
            </main>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>AGUAS AZ &copy; 2020</span>
                    </div>
                </div>
            </footer>

        </div>
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?= $this->section('scripts') ?>
    <script src="<?= assets('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= assets('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= assets('js/handlers/functions.js') ?>"></script>
    <script src="<?= assets('js/sb-admin-2.min.js') ?>"></script>
    <script src="<?= assets('vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= assets('vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= assets('js/demo/datatables-demo.js') ?>"></script>


</div>
</body>
</html>