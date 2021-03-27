<?php $this->layout("worker::_template_") ?>
<h1 class="h3 mb-2 text-gray-800">Clientes</h1>
<p class="mb-4">Abaixo sita uma tabela com os clientes registrados, começando pelos recentes e também poderá tomar accões
    por cada um deles ou personalizar a forma de apresentação
    através das opções abaixo</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Celular</th>
                    <th>Morada</th>
                    <th>Data de Adesão</th>
                    <th>Acções</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($dados as $cliente) :
                    echo "<tr id='{$cliente->ID}'>";
                    echo "<td>{$cliente->ID}</td>";
                    echo "<td>{$cliente->Nome}</td>";
                    echo "<td>{$cliente->Celular}</td>";
                    echo "<td>{$cliente->Morada}</td>";
                    echo "<td>{$cliente->Data_Adesao}</td>";

                    if ($cliente->Estado == 1) :
                        echo '<td class="text-center">
                                  <a href="'.$router->route("admin.clientes.editar",["id"=>$cliente->ID]).'" class="btn btn-info btn-circle btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                  </a>
                                  <button onclick="deactivate_client('.$cliente->ID.',1)" class="btn btn-warning btn-circle btn-sm">
                                    <i class="fas fa-times-circle"></i>
                                  </button>
                                </td>';
                        echo "</tr>";
                    else:
                        echo '<td class="text-center">
                                  <a href="'.$router->route("admin.clientes.editar",["id"=>$cliente->ID]).'" class="btn btn-info btn-circle btn-sm">
                                      <i class="fas fa-pencil-alt"></i>
                                  </a>
                                  <button onclick="deactivate_client('.$cliente->ID.',2)" class="btn btn-success btn-circle btn-sm">
                                      <i class="fas fa-check-circle"></i>
                                  </button>
                              </td>';
                        echo "</tr>";
                    endif;
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header pb-1">
        <h5 class="card-title text-primary p-0">Cadastro de Cliente</h5>
    </div>
    <div class="card-body">
        <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
            <button type="button" class="close" onclick="hide()">
                <span>&times;</span>
            </button>
        </div>
        <form class="user" id="form">
            <div class="form-group">
                <label for="Nome" class="pl-2">Nome Completo</label>
                <input value="" type="text" name="Nome" class="form-control form-control-user" maxlength="255" id="Nome"
                       placeholder="Digite aqui o nome do cliente">
            </div>
            <div class="form-group">
                <label for="Celular" class="pl-2">Número de Celular</label>
                <input type="text" name="Celular" class="form-control form-control-user" maxlength="9" id="Celular"
                       placeholder="Digite aqui o número de celular">
            </div>
            <div class="form-group">
                <label for="Morada" class="pl-2">Morada</label>
                <input type="text" name="Morada" class="form-control form-control-user" maxlength="150" id="Morada"
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
<script>
    let form = document.getElementById('form');
    let page = "<?= $router->route("admin.clientes.cadastrar")?>";
    let lastId = <?= $dados[array_key_last($dados)]->ID ?>;
    let pagestate = "<?= $router->route("admin.clientes.estado")?>";
</script>

