<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SGFA - Login</title>
    <link href="<?= assets('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= assets('css/sb-admin-2.min.css')?>" rel="stylesheet">
</head>

<body class="bg-gradient-light">

<div class="container">
    <div id="ajaxloader" class="loader" hidden></div>

    <div class="row justify-content-center" style="padding-top: 20%;">
        <div class="col-md-6">
            <img src="<?=assets("img/SGFA.png")?>" class="mb-1" height="150" width="auto">
            <h1>Alterar a password</h1>
            <p>No primeiro login deve alterar a password gerada pelo sistema para uma a sua escolha</p>
            <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                <button type="button" class="close" onclick="hide()">
                    <span>&times;</span>
                </button>
            </div>
            <form action="" id="form">
                <div class="form-group">
                    <input class="form-control" name="senha" type="password" placeholder="Digite a nova password" required>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success rounded ">
                        Alterar <i class="fa fa-check-circle"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>



</body>
<!-- Bootstrap core JavaScript-->
<script src="<?= assets('vendor/jquery/jquery.js') ?>"></script>
<script>
    let page = "<?= $router->route("auth.change") ?>";
    let form = document.getElementById("form");
</script>
<script src="<?= assets('js/handlers/functions.js') ?>"></script>
<script src="<?= assets('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?= assets('vendor/jquery-easing/jquery.easing.min.js')?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?= assets('js/sb-admin-2.min.js') ?>"></script>



</html>