<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="O SFGA é um sistema de gestão de furos de água privados, com este sistema é ...">
    <meta name="keywords" content="SGFA,Sistema de gestao de furo de agua,nextgen it">
    <meta name="author" content="NextGeneration IT">

    <title><?= $_ENV['LICENSED_TO'] ?> | Sistema de Gestão de Furo de Água</title>

    <link href="<?= assets('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= assets('css/sb-admin-2.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= assets('css/custom.css') ?>" rel="stylesheet" type="text/css">
    <script src="<?= assets('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,800;1,500;1,700&display=swap"
          rel="stylesheet">
    <style>
        body{
            font-family: "Montserrat";
        }
    </style>
</head>

<body id="page-top">
<div id="ajaxloader" hidden></div>

<div id="wrapper">
    <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand mt-4 d-flex align-items-center justify-content-center"
           href="<?= $router->route("user.home") ?>">
            <div class="sidebar-brand-text bg-white p-1 rounded">
                <img src="<?= assets("img/SGFA.png") ?>" height="60px" width="180px">
            </div>
        </a>

        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("user.home") ?>">
                <i class="fas fa-fw fa-chart-area" style="font-size: 1rem"></i>
                Painel Inicial
            </a>
        </li>

        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("user.invoices") ?>">
                <i class="fas fa-fw fa-file-invoice-dollar" style="font-size: 1rem"></i>
                Facturas
            </a>
        </li>

        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("user.receipts") ?>">
                <i class="fas fa-fw fa-receipt" style="font-size: 1rem"></i>
                Recibos
            </a>
        </li>

        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("user.pay") ?>">
                <i class="fas fa-fw fa-money-bill-wave" style="font-size: 1rem"></i>
                Pagar online
            </a>
        </li>

        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link text-break" href="<?= $router->route("user.config") ?>">
                <i class="fas fa-fw fa-cogs" style="font-size: 1rem"></i>
                Definições
            </a>
        </li>


        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline pt-5">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

        <div class="font-weight-bold text-center mt-5 pt-lg-5 text-break d-none d-md-inline">
            <p class="text-white">SGFA <?= $_ENV['APP_VERSION'] ?></p>
            <p class="text-white"><?= $_ENV['LICENSED_TO'] ?> &copy; 2021</p>
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

                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">CLIENTE</span>
                            <img class="img-profile rounded-circle"
                                 src="https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= $router->route("auth.logout") ?>">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                            Sair
                        </a>
                    </li>

                </ul>

            </nav>
            <main>
                <div class="container-fluid">

                    <div id="ajaxloader" class="loader" hidden></div>
                    <?= $this->section('content') ?>

                </div>
            </main>
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