<?php $this->layout("_template")  ?>
<div class="row align-items-center justify-content-center">

    <div class="col-xl-6 col-lg-6 col-md-6">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-3">
                            <div class="text-left">
                                <img src="<?= assets("img/SGFA.png") ?>" class="text-center mb-4" height="70px"
                                     width="200px" alt="SGFA_logo">
                                <h1 class="h4 text-gray-900 mb-4">
                                    <button class="btn btn-primary btn-circle">2</button>
                                    Registar o administrador
                                </h1>
                                <?= $this->show_alert() ?>
                            </div>
                            <form class="user" action="<?= $router->route("form.admin") ?>" method="post">
                                <div class="form-group">
                                    <label for="name"><i class="fa fa-user"></i> Nome</label>
                                    <input type="text" class="form-control" id="name"
                                           name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="surname"><i class="fa fa-user"></i> Apelido</label>
                                    <input type="text" class="form-control" id="surname"
                                           name="surname" required>
                                </div>
                                <div class="form-group">
                                    <label for="username"><i class="fa fa-at"></i> Nome de usuário</label>
                                    <input type="text" class="form-control" id="username"
                                           name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password"><i class="fa fa-key"></i> Senha</label>
                                    <input type="password" class="form-control" id="password"
                                           name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="repeat_password"><i class="fa fa-key"></i> Repetir senha</label>
                                    <input type="password" class="form-control" id="repeat_password"
                                           name="repeat_password" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success">
                                        Continuar <i class="fa fa-arrow-circle-right"></i>
                                    </button>
                                </div>
                            </form>
                            <br>
                            <div class="text-center">
                                <a class="small" href="https://nextgenit-mz.com">NextGen IT &copy; SGFA
                                    v<?= SITE["version"] ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>