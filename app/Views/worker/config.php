<?php $this->layout("worker::_template_") ?>
<h1 class="h3 mb-2 font-weight-bold text-gray-800"><i class="fa fa-cogs"></i> Definições</h1>
<p class="mb-4 text-dark">Abaixo encontra os dados do negócio e acções que pode realizar.<br>
<span class="font-weight-bold">Se pretender alterar algum dado, entre em contacto com o administrador máximo
    (<span class="font-weight-normal">Root</span>)</span>

<div class="row py-4">
    <div class="col-md-6 col-xl-6 p-1">
        <div class="card">
            <div class="card-body">
                <h5 class="font-weight-bold text-primary card-title">Dados do negócio</h5>
                <p>Comunique a todos os seus clientes sobre as mudanças, pode utilizar o <b>Envio
                        de SMS</b> para <b>Todos os clientes</b> na página <b>Mensagens</b>.</p>
                <form onsubmit="event.preventDefault()" class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="expiry_mode"><i class="fa fa-question-circle"></i> Modo de expiração de
                                factura</label>
                            <select class="form-control" disabled>
                                <option <?= $config['expiry_mode'] == 1 ? 'selected' : '' ?> value="1">
                                    X dias após a emissão
                                </option>
                                <option <?= $config['expiry_mode'] == 2 ? 'selected' : '' ?> value="2">
                                    Até dia X do mês seguinte
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_expiry"><i class="fa fa-calendar"></i> Dia da expiração</label>
                            <input type="number" class="form-control" value="<?= $config['expiry_date'] ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-money-bill"></i> Preço por m<sup>3</sup></label>
                            <input class="form-control form-control-user" value="<?= $config['price_per_m3'] ?>"
                                   type="number" disabled>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label><i class="fas fa-badge-dollar"></i> Tarifa Base</label>
                            <input class="form-control form-control-user" value="<?= $config['base_price'] ?>"
                                   type="number" disabled>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-badge-dollar"></i> Consumo Máximo da Tarifa Base</label>
                            <input class="form-control form-control-user" value="<?= $config['base_volume'] ?>"
                                   type="number" disabled>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-dollar-sign"></i> Valor de Multa</label>
                            <input class="form-control form-control-user" value="<?= $config['fine'] ?>"
                                   type="number" disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-6 p-1">
        <div class="card">
            <div class="card-body">
                <h5 class="font-weight-bold text-primary card-title">Preferências</h5>
                <div class="custom-checkbox custom-switch">
                    <input id="check" type="checkbox" class="custom-control-input"
                        <?= (int)$config['auto_sms'] == 0 ? '' : 'checked' ?> onchange="switch_sms(this)" name="auto_sms_warn">
                    <label for="check" class="custom-control-label">
                        <i class="fas fa-comment-alt"></i> <span style="font-size: 1rem">SMS's de Aviso</span>
                    </label>
                </div>
                <p>*Se desativar esta opção o sistema deixará de enviar todas as SMS's automaticamente para os clientes
                    para aviso sobre a emissão de novas facturas, recibos, lembrete de pagamento e de aviso de
                    multa!</p>

                <form onsubmit="post_password(event)">
                    <h5 class="font-weight-bold"> Mudar a sua senha</h5>
                    <div class="form-group">
                        <label for=""><i class="fas fa-lock-open"></i> Senha atual</label>
                        <input class="form-control" name="password" type="password">
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-lock"></i> Nova senha</label>
                        <input class="form-control" name="new_password" type="password">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            Atualizar senha <i class="fa fa-check-circle"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col col p-1">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary font-weight-bold">As suas informações</h5>
                <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
                    <button type="button" class="close" onclick="hide()">
                        <span>&times;</span>
                    </button>
                </div>
                <form onsubmit="update_admin_info(event)">
                    <div class="form-group">
                        <label for=""><i class="fas fa-user"></i> Nome</label>
                        <input class="form-control form-control-user" value="<?= $you['name'] ?>"
                               type="text" disabled>
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-user"></i> Apelido</label>
                        <input class="form-control form-control-user" value="<?= $you['surname'] ?>"
                               type="text" disabled>
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-phone-alt"></i> Celular</label>
                        <input class="form-control form-control-user" value="<?= $you['phone'] ?>"
                               type="text" disabled>
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-user-lock"></i> Usuário</label>
                        <input class="form-control form-control-user" value="<?= $you['credentials']['username'] ?>"
                               type="text" disabled>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php $this->start('scripts') ?>
<script>let last_admin_id = <?= $admins[0]['id'] ?? 0 ?>;</script>
<script src="<?= assets('js/config.js') ?>"></script>
<?php $this->end() ?>
