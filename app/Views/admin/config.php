<?php $this->layout("admin::_template_") ?>
<h1 class="h3 mb-2 font-weight-bold text-gray-800"><i class="fa fa-cogs"></i> Definições</h1>
<p class="mb-4 text-dark">Abaixo encontra os dados do negócio que podem ser alterados, algumas preferências que pode
    ajustar e acções que pode realizar.</p>

<div class="row py-4">
    <div class="col-md-7 col-xl-7 p-1">
        <div class="card">
            <div class="card-body">
                <h5 class="font-weight-bold text-primary card-title">Dados do negócio</h5>
                <p>Comunique a todos os seus clientes sobre as mudanças, pode utilizar o <b>Envio
                        de SMS</b> para <b>Todos os clientes</b> na página <b>Mensagens</b>.</p>
                <form onsubmit="post_config(event)" class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="expiry_mode"><i class="fa fa-question-circle"></i> Modo de expiração de
                                factura</label>
                            <select class="form-control" name="expiry_mode" id="expiry_mode" required>
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
                            <input type="number" max="30" min="1" class="form-control"
                                   name="expiry_date" value="<?= $config['expiry_date'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-money-bill"></i> Preço por m<sup>3</sup></label>
                            <input class="form-control form-control-user" value="<?= $config['price_per_m3'] ?>"
                                   type="number" step="1.00" min="1" name="price_per_m3" required>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label><i class="fas fa-badge-dollar"></i> Tarifa Base</label>
                            <input class="form-control form-control-user" value="<?= $config['base_price'] ?>"
                                   type="number" step="1.00" name="base_price" min="0" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-badge-dollar"></i> Consumo Máximo da Tarifa Base</label>
                            <input class="form-control form-control-user" value="<?= $config['base_volume'] ?>"
                                   type="number" name="base_volume" step="1.00" min="0" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-dollar-sign"></i> Valor de Multa</label>
                            <input class="form-control form-control-user" value="<?= $config['fine'] ?>"
                                   type="number" name="fine" step="1.00" min="0" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                Atualizar <i class="fa fa-check-circle"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5 col-xl-5 p-1">
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

    <div class="col-md-6 col-xl-6 p-1">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary font-weight-bold">Editas as suas informações</h5>
                <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
                    <button type="button" class="close" onclick="hide()">
                        <span>&times;</span>
                    </button>
                </div>
                <form onsubmit="update_admin_info(event)">
                    <div class="form-group">
                        <label for=""><i class="fas fa-user"></i> Nome</label>
                        <input class="form-control form-control-user" value="<?= $you['name'] ?>"
                               name="name" required type="text">
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-user"></i> Apelido</label>
                        <input class="form-control form-control-user" value="<?= $you['surname'] ?>"
                               name="surname" required type="text">
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-phone-alt"></i> Celular</label>
                        <input class="form-control form-control-user" value="<?= $you['phone'] ?>"
                               name="phone" type="text">
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-user-lock"></i> Usuário</label>
                        <input class="form-control form-control-user" value="<?= $you['credentials']['username'] ?>"
                               name="username" type="text" required>
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-user-lock"></i> Confirmar usuário</label>
                        <input class="form-control form-control-user" name="repeat-username" type="text">
                        <p>N.B: Só preencher se pretender mudar o seu nome de usuário e, deve fazê-lo de forma igual no
                            campo anterior a este com o novo nome</p>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="submit" class="btn btn-success">
                            Gravar <i class="fas fa-check-circle"></i>
                        </button>
                        &nbsp;
                        <button type="reset" class="btn btn-danger">
                            Cancelar <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-md-6 col-xl-6 p-1">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary font-weight-bold">Criar novo usuário administrativo</h5>
                <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
                    <button type="button" class="close" onclick="hide()">
                        <span>&times;</span>
                    </button>
                </div>
                <p>Por questões de segurança, pode existir somente 1 usuário com os previlégios máximos (<b>Root</b> e que é você),
                    pois com este tipo de acesso pode modificar todos os dados do negócio, preferências e entre outras
                    acções.</p>
                <form onsubmit="post_new_user(event)">
                    <div class="form-group">
                        <label for=""><i class="fas fa-user"></i> Nome</label>
                        <input class="form-control form-control-user" name="name" required type="text">
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-user"></i> Apelido</label>
                        <input class="form-control form-control-user" name="surname" required type="text">
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-phone-alt"></i> Celular</label>
                        <input class="form-control form-control-user" name="phone" type="text">
                    </div>
                    <div class="form-group">
                        <label for=""><i class="fas fa-shield"></i> Previlégio</label>
                        <select name="role" disabled class="form-control form-control-user" id="">
                            <option>Administrador</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="submit" class="btn btn-success">
                            Criar <i class="fas fa-check-circle"></i>
                        </button>
                        &nbsp;
                        <button type="reset" class="btn btn-danger">
                            Cancelar <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xl-12 p-1">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary font-weight-bold">Usuários administrativos</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Apelido</th>
                                <th>Celular</th>
                                <th>Username</th>
                                <th>Estado</th>
                                <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($admins as $admin) :
                                echo "<tr id='adm{$admin['id']}'>";
                                echo "<td>{$admin['name']}</td>";
                                echo "<td>{$admin['surname']}</td>";
                                echo "<td>{$admin['phone']}</td>";
                                echo "<td>{$admin['credentials']['username']}</td>";
                                echo "<td>". ($admin['status'] == 1 ? 'Activo' : 'Inactivo') ."</td>";
                                echo '<td class="text-center">
                                        <button title="Editar informações" onclick="open_edit_modal('.$admin['id'].')" class="btn btn-dark">
                                             <i class="fas fa-user-edit"></i>
                                         </button>
                                        <button title="Modificar estado" onclick="status_of_admin('. $admin['id'] .','.intval($admin['status']).')" 
                                            class="btn btn-'.($admin['status'] == "1" ? 'danger' : 'success').'">
                                            <i class="fas fa-'.($admin['status'] == "1" ? 'times' : 'check').'-circle"></i>
                                        </button>
                                     </td>';
                                echo "</tr>";
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title font-weight-bold" id="edit-modal-title">Editar informações do administrador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <form action="" onsubmit="post_edit_user(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="pl-2">Nome</label>
                        <input type="text" name="name" class="form-control" maxlength="255" id="edit_name"
                               placeholder="Digite aqui o nome do cliente">
                    </div>
                    <div class="form-group">
                        <label for="surname" class="pl-2">Apelido</label>
                        <input type="text" name="surname" class="form-control" maxlength="255" id="edit_surname"
                               placeholder="Digite aqui o apelido do cliente">
                    </div>
                    <div class="form-group">
                        <label for="phone" class="pl-2">Número de Celular</label>
                        <input type="text" name="phone" class="form-control" maxlength="9" id="edit_phone"
                               placeholder="Digite aqui o número de celular">
                    </div>
                    <input type="hidden" value="" name="id" id="edit_id">
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-success">
                        Gravar <i class="fas fa-check-circle"></i>
                    </button>
                    <button type="reset" class="btn btn-danger" data-dismiss="modal">
                        Cancelar <i class="fas fa-times-circle"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->start('scripts') ?>
<script>let last_admin_id = <?= $admins[0]['id'] ?? 0 ?>;</script>
<script src="<?= assets('js/config.js') ?>"></script>
<?php $this->end() ?>
