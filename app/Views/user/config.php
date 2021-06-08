<?php $this->layout('user::_template_'); ?>
<h1 class="h3 mb-2 font-weight-bold text-gray-800"><i class="fa fa-cogs"></i> Configurações</h1>
<p class="mb-4 text-dark">Abaixo encontra os seus dados pessoais e algumas preferências que pode ajustar.</p>
<p class="font-weight-bolder">NOTA: Se tiver algum dado errado ou que precisa de atualizado entre em contacto connosco</p>

<div class="row pt-4">
    <div class="col-md-5 col-xl-6 p-1">
        <div class="card">
            <div class="card-body">
                <h5 class="font-weight-bold card-title">Dados pessoais</h5>

                <div class="form-group">
                    <label for=""><i class="fas fa-user"></i> Nome</label>
                    <input class="form-control form-control-user" disabled value="<?= $client->name ?>" type="text">
                </div>
                <div class="form-group">
                    <label for=""><i class="fas fa-user"></i> Apelido</label>
                    <input class="form-control form-control-user" disabled value="<?= $client->surname ?>" type="text">
                </div>
                <div class="form-group">
                    <label for=""><i class="fas fa-map-marked"></i> Morada</label>
                    <input class="form-control form-control-user" disabled value="<?= $client->address ?>" type="text">
                </div>
                <div class="form-group">
                    <label for=""><i class="fas fa-phone-alt"></i> Número de Celular</label>
                    <input class="form-control form-control-user" disabled value="+258 <?= $client->phone ?>" type="text">
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-5 col-xl-6 p-1">
        <div class="card">
            <div class="card-body">
                <h5 class="font-weight-bold card-title">Preferências</h5>

                <form action="" method="post">
                    <div class="custom-checkbox custom-switch">
                        <input id="check" type="checkbox" class="custom-control-input" checked name="auto_sms_warn">
                        <label for="check" class="custom-control-label">
                            <i class="fas fa-comment-alt"></i> <span  style="font-size: 1rem">SMS's de Aviso</span>
                        </label>
                    </div>
                    <p>*Se desativar esta opção deixa de receber as SMS's automáticas de aviso
                        sobre a emissão de novas facturas, recibos e até de atrasos nos pagamentos.</p>
                    <h5 class="font-weight-bold"> Mudar a senha</h5>
                    <div class="form-group">
                        <label for=""><i class="fas fa-lock-open"></i> Senha atual</label>
                        <input class="form-control form-control-user" type="text">
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-lock"></i> Nova senha</label>
                        <input class="form-control form-control-user" type="text">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            Atualizar <i class="fa fa-check-circle"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

