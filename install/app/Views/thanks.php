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
                                <h1 class="h4 text-gray-900 mb-4 text-center">
                                    <i class="fa text-success fa-check-circle"></i>
                                    Instalado com sucesso
                                </h1>
                                <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                                    <button type="button" class="close" onclick="hide()">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <p id="welcome_text" class="pb-3">
                                    <b>Parabéns por finalizar a instalação do SGFA !</b><br>
                                    Para começar a utilizar o sistema aceda à página de login e introduza
                                    as credenciais criadas anteriormente
                                </p>
                            </div>
                            <div class="text-center">
                                <a href="/" class="btn btn-success">
                                    Ir para login <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
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