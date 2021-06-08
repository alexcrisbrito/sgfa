<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $_ENV['LICENSED_TO'] ?> | SGFA <?= $_ENV['APP_VERSION'] ?></title>
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
    <div class="row align-items-center justify-content-center">

        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="col p-5">
                        <div class="text-left">
                            <img src="<?= assets("img/SGFA.png") ?>" class="text-center img-fluid" height="90px"
                                 width="260px" alt="logo">
                            <h3 class="text-gray-900 my-4 text-center font-weight-bolder">Login <i class="fa fa-sign-in"></i></h3>
                            <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                                <button type="button" class="close" onclick="hide()">
                                    <span><i class="far fa-times-circle"></i></span>
                                </button>
                            </div>
                        </div>
                        <form class="user" id="form">
                            <div class="form-group font-weight-bold">
                                <label><i class="fa fa-user"></i> Usuário</label>
                                <input type="text" class="form-control" name="id" required>
                            </div>
                            <div class="form-group font-weight-bold">
                                <label><i class="fa fa-lock"></i> Senha</label>
                                <input type="password" class="form-control" name="pwd" required>
                            </div>
                            <a class="text-primary" data-target="#forgot_modal" data-toggle="modal">Não se lembra das suas credenciais <i class="far fa-question-circle"></i></a>
                            <div class="form-group text-center py-4">
                                <button type="submit" class="btn btn-success">
                                    Acessar <i class="fa fa-sign-in-alt"></i>
                                </button>
                            </div>
                        </form>
                        <div class="text-center">
                            <a class="small font-weight-bold text-center" href="https://nextgenit-mz.com">
                                NextGeneration IT &copy; SGFA <?= $_ENV["APP_VERSION"] ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="forgot_modal" tabindex="-1" role="dialog"
         aria-labelledby="forgot_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bold" id="forgot_modal_title">Esqueceu as credenciais ?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="row justify-content-center pb-2 pt-1">
                    <button type="button" class="btn btn-success rounded" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= assets('vendor/jquery/jquery.js') ?>"></script>
<script>
    let page = "<?= $router->route("auth.login") ?>";
</script>
<script src="<?= assets('js/login.js') ?>"></script>
<script src="<?= assets('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= assets('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= assets('js/sb-admin-2.min.js') ?>"></script>
</body>

</html>

