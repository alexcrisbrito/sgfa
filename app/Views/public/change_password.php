<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $_ENV['LICENSED_TO'] ?> | Mudar a senha gerada</title>
    <link href="<?= assets('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= assets('css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= assets('css/custom.css') ?>" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,800;1,500;1,700&display=swap"
          rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat';
        }
    </style>
</head>

<body class="bg-gradient-dark m-auto">
<div id="ajaxloader" hidden></div>
<div class="container">
    <!-- Outer Row -->
    <div class="row align-items-center justify-content-center">

        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="col p-5">
                        <div class="text-left">
                            <img src="<?= assets("img/SGFA.png") ?>" class="text-center img-fluid" height="90px"
                                 width="260px" alt="logo">
                            <h1 class="h2 text-gray-600 my-4 text-center font-weight-bolder">Mudar a senha <i class="fa fa-lock"></i></h1>
                            <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                                <button type="button" class="close" onclick="hide()">
                                    <span>&times;</span>
                                </button>
                            </div>
                        </div>
                        <p>No primeiro login é necessário trocar a senha gerada pelo sistema para garantir a
                            segurança da sua conta, por favor, faça uso de uma senha segura e fácil de lembrar e, com
                            pelo menos 6 dígitos</p>
                        <form id="form">
                            <div class="form-group font-weight-bold">
                                <label><i class="fa fa-lock"></i> Nova senha</label>
                                <input type="password" class="form-control" name="password" minlength="6" required>
                            </div>
                            <div class="form-group font-weight-bold">
                                <label><i class="fa fa-lock"></i> Repita a nova senha</label>
                                <input type="password" class="form-control" name="repeat-password" minlength="6" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success">
                                    Continuar <i class="fa fa-arrow-alt-circle-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= assets('vendor/jquery/jquery.js') ?>"></script>
<script>
    let page = "<?= $router->route("auth.change") ?>";
</script>
<script src="<?= assets('js/login.js') ?>"></script>
<script src="<?= assets('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= assets('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= assets('js/sb-admin-2.min.js') ?>"></script>
</body>

</html>
