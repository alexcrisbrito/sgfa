<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Aguas AZ - Login</title>
    <link href="<?= assets('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= assets('css/sb-admin-2.min.css')?>" rel="stylesheet">
</head>

<body class="bg-gradient-light m-auto">

<div class="container">
    <div id="ajaxloader" class="loader" hidden></div>
    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image" style="background: url('<?=assets("img/080.jpg")?>');background-repeat: no-repeat;background-size: 100% 100%"></div>
                        <div class="col-lg-6">
                            <div class="p-3">
                                <div class="text-center">
                                    <img src="<?=assets("img/SGFA.png")?>" class="mb-1" height="150" width="auto">
                                    <h1 class="h4 text-gray-900 mb-4">Bem vindo !</h1>
                                    <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                                        <button type="button" class="close" onclick="hide()">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <form class="user" id="form">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="exampleInputEmail" name="id" aria-describedby="emailHelp" placeholder="Digite o seu ID">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" name="pwd" id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Acessar <i class="fa fa-sign-in"></i>
                                    </button>
                                </form>
                                <br>
                                <br>
                                <div class="text-center">
                                    <a class="small" href="https://nextgenit-mz.com">&copy; SGFA v<?= SITE["version"] ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Bem vindo à nova versão</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 class="text-center">BEM VINDO À NOVA VERSÃO DO NOSSO SISTEMA DE GESTÃO !</h5>
        <p>Estamos sempre preocupados com a qualidade de serviço que entregamos aos nossos clientes, e esta nova versão do nosso sistema é a prova viva disso, um sistema mais rápido, seguro, fiel e inovador, esperamos que aproveite as novas funcionalidades !</p>
      </div>
      <div class="row justify-content-center pb-2 pt-1">
        <button type="button" class="btn btn-primary rounded" data-dismiss="modal">OK</button>
        </div>
    </div>
  </div>
</div></div>

<!-- Bootstrap core JavaScript-->
<script src="<?= assets('vendor/jquery/jquery.js') ?>"></script>
<script>
    let page = "<?= $router->route("auth.login") ?>";
</script>
<script src="<?= assets('js/handlers/login.js') ?>"></script>
<script src="<?= assets('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?= assets('vendor/jquery-easing/jquery.easing.min.js')?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?= assets('js/sb-admin-2.min.js') ?>"></script>
<!--
Show modal on screen load
<script type="text/javascript">
    $(window).on('load',function(){
        $('#exampleModalCenter').modal('show');
    });
</script>-->
</body>

</html>

