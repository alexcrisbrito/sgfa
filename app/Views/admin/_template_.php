<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="O SFGA é um sistema de gestão de furos de água privados, com este sistema é ...">
    <meta name="keywords" content="SGFA,Sistema de gestao de furo de agua,nextgen it">
    <meta name="author" content="NextGeneration IT">

    <title><?= $_ENV['LICENSED_TO'] ?> - Sistema de Gestão de Furo de Água</title>

    <!-- Custom fonts for this template-->
    <link href="<?= assets('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="<?= assets('css/sb-admin-2.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= assets('css/custom.css') ?>" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,800;1,500;1,700&display=swap"
          rel="stylesheet">
    <script src="<?= assets('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        body{
            font-family: "Montserrat";
        }
    </style>
</head>

<body id="page-top">
<div id="ajaxloader" hidden></div>

<div id="wrapper">

    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand mt-4 d-flex align-items-center justify-content-center"
           href="<?= $router->route("admin.home") ?>">
            <div class="sidebar-brand-text bg-white p-1 rounded">
                <img src="<?= assets("img/SGFA.png") ?>" height="60px" width="180px">
            </div>
        </a>

        <li class="nav-item active pt-4">
            <a class="nav-link" href="<?= $router->route("admin.home") ?>">
                <i style="font-size: 1rem" class="fas fa-fw fa-tachometer-alt"></i>
                <span style="font-size: 1rem">Painel Inicial</span></a>
        </li>

        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("admin.invoices") ?>">
                <i style="font-size: 1rem" class="fas fa-fw fa-file-invoice-dollar"></i>
                <span style="font-size: 1rem">Facturas</span></a>
        </li>

        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("admin.receipts") ?>">
                <i style="font-size: 1rem" class="fas fa-fw fa-receipt"></i>
                <span style="font-size: 1rem">Recibos</span></a>
        </li>

        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("admin.financial") ?>">
                <i style="font-size: 1rem" class="fas fa-fw fa-money-bill-wave"></i>
                <span style="font-size: 1rem">Financeiro</span></a>
        </li>

        <hr class="sidebar-divider my-0">

        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("admin.clients") ?>">
                <i style="font-size: 1rem" class="fas fa-fw fa-users"></i>
                <span style="font-size: 1rem">Clientes</span></a>
        </li>
        <hr class="sidebar-divider my-0">

        <!--        <li class="nav-item active">-->
        <!--            <a class="nav-link" href="$router->route("admin.colab") ">-->
        <!--                <i class="fas fa-fw fa-user-circle"></i>-->
        <!--                <span>Colaboradores</span></a>-->
        <!--        </li>-->
        <!--        <hr class="sidebar-divider my-0">-->

        <li class="nav-item active">
            <a class="nav-link" href="<?= $router->route("admin.sms") ?>">
                <i style="font-size: 1rem" class="fas fa-fw fa-comment-alt"></i>
                <span style="font-size: 1rem">Mensagens</span></a>
        </li>
        <hr class="sidebar-divider my-0 mb-2">

        <div class="text-center d-none d-md-inline pt-3">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

        <div class="text-center mt-5 d-none d-md-inline">
            <p class="text-white">SGFA <?= $_ENV['APP_VERSION'] ?></p>
            <p class="text-white">Licenciado: <?= $_ENV['LICENSED_TO'] ?></p>
            <p class="text-white">Build: <?= $_ENV['APP_BUILD_NUMBER'] ?></p>
            <p class="text-white">NextGen IT &copy; 2021</p>
        </div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>

                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">ADMINISTRADOR</span>
                            <img class="img-profile rounded-circle"
                                 src="https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#" onclick="enter_fullscreen(event)">
                            <i class="fa fa-expand-arrows mr-2"></i>
                            Ecrã cheio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= $router->route("admin.config") ?>">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2"></i>
                            Definições
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
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <?= $this->section('content'); ?>

                </div>
                <!-- End of Main Content -->
            </main>

        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
            integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"
            integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?= $this->section('scripts') ?>
    <script src="<?= assets('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= assets('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= assets('js/functions.js') ?>"></script>
    <script src="<?= assets('js/sb-admin-2.min.js') ?>"></script>
    <script src="<?= assets('vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= assets('vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= assets('js/demo/datatables-demo.js') ?>"></script>
</div>
</body>
</html>