<?php $this->layout("worker::_template_") ?>
<h1 class="h3 mb-2 font-weight-bold text-gray-800">
    <i class="fa fa-user-circle"></i> Clientes
</h1>
<p class="mb-4">Abaixo sita uma tabela com os clientes registrados, começando pelos recentes e também poderá tomar
    accões
    por cada um deles ou personalizar a forma de apresentação
    através das opções abaixo</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Celular</th>
                    <th>Morada</th>
                    <th>Username</th>
                    <th>Data de Adesão</th>
                    <th>Estado</th>
                    <th>Acções</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($clients as $client) :
                    echo "<tr id='cl{$client['id']}'>";
                    echo "<td>{$client['id']}</td>";
                    echo "<td>{$client['name']} {$client['surname']}</td>";
                    echo "<td>{$client['phone']}</td>";
                    echo "<td>{$client['address']}</td>";
                    echo "<td>{$client['credentials']['username']}</td>";
                    echo "<td>{$client['date_added']}</td>";
                    echo "<td>". ($client['status'] == "1" ? 'Activo' : 'Inactivo') ."</td>";
                    echo '<td class="text-center">
                             <button title="Editar informações" onclick="open_edit_modal('.$client['id'].')" class="btn btn-dark">
                                 <i class="fas fa-user-edit"></i>
                             </button>
                             <button title="Modificar estado" onclick="status_of_client('. $client['id'] .','.intval($client['status']).')" 
                                class="btn btn-'.($client['status'] == "1" ? 'danger' : 'success').'">
                                 <i class="fas fa-'.($client['status'] == "1" ? 'times' : 'check').'-circle"></i>
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

<div class="card shadow mb-4">
    <div class="card-header pb-1">
        <h5 class="card-title text-primary font-weight-bold p-0">Registro de Cliente</h5>
    </div>
    <div class="card-body">
        <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
            <button type="button" class="close" onclick="hide()">
                <span>&times;</span>
            </button>
        </div>
        <form class="user" onsubmit="post_client(event)">
            <div class="form-group">
                <label for="name" class="pl-2">Nome</label>
                <input type="text" name="name" class="form-control" maxlength="255" id="name"
                       placeholder="Digite aqui o nome do cliente">
            </div>
            <div class="form-group">
                <label for="surname" class="pl-2">Apelido</label>
                <input type="text" name="surname" class="form-control" maxlength="255" id="surname"
                       value="" placeholder="Digite aqui o apelido do cliente">
            </div>
            <div class="form-group">
                <label for="phone" class="pl-2">Número de Celular</label>
                <input type="text" name="phone" class="form-control" maxlength="9" id="phone"
                       placeholder="Digite aqui o número de celular">
            </div>
            <div class="form-group">
                <label for="address" class="pl-2">Morada</label>
                <input type="text" name="address" class="form-control" maxlength="150" id="address"
                       placeholder="Digite aqui a morada">
            </div>
            <div class="form-group col-sm-5">
                <div class="row-cols-2">
                    <button type="submit" class="btn btn-success">Registrar <i class="fas fa-check-circle"></i></button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger">Cancelar <i class="fas fa-times-circle"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title font-weight-bold" id="edit-modal-title">Editar informações do cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <form action="" onsubmit="post_edit_client(event)">
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
                    <div class="form-group">
                        <label for="address" class="pl-2">Morada</label>
                        <input type="text" name="address" class="form-control" maxlength="150" id="edit_address"
                               placeholder="Digite aqui a morada">
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
<script src="<?= assets('js/clients.js') ?>" type="text/javascript"></script>
<script>
    let last_client_id = <?= $clients[0]->id ?? 0 ?>;
</script>
<?php $this->end() ?>
