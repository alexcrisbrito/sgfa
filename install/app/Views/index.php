<?php $this->layout("_template") ?>
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
                                <h1 class="h4 text-gray-900 mb-4 text-center">Bem vindo ao SGFA !</h1>
                                <?= $this->show_alert() ?>
                                <p id="welcome_text" class="pb-3">
                                    É com grande prazer que damos-lhe as boas vindas ao nosso Sistema de Gestão de
                                    Furos de Água, que apartir de hoje o vai ajudar a fazer a gestão do seu negócio,
                                    esperamos que o sistema consiga alcançar os resultados que espera !<br><br>
                                    Para continuar com a configuração, introduza a <b>chave única de ativação do produto</b>
                                    que lhe foi fornecida <b>exatamente como vem descrita</b>, no campo abaixo
                                </p>
                            </div>
                            <form class="user" action="<?= $router->route("form.index") ?>" method="post">
                                <div class="form-group">
                                    <label for="activation-key"><i class="fa fa-key"></i> Chave de Ativação</label>
                                    <input type="text" class="form-control" id="activation-key"
                                           name="activation_key" placeholder="XXXXXX-XX-XXXX"
                                           pattern="[A-Z0-9][-][A-Z0-9][-][A-Z][A-Z0-9]{6,1,2,1,4}" maxlength="14" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success">
                                        Continuar <i class="fa fa-check-circle"></i>
                                    </button>
                                </div>
                            </form>
                            <br>
                            <div class="text-center">
                                <a class="small" href="https://nextgenit-mz.com">NextGen IT &copy; SGFA
                                    <?= $_ENV['APP_VERSION'] ?></a>
                                <p class="pt-2">Build N<sup>o</sup> <?= $_ENV['APP_BUILD_NUMBER'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>