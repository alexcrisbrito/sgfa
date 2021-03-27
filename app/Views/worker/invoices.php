<?php $this->layout("worker::_template_") ?>

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Facturas</h1>
<p class="mb-4">Abaixo sita uma tabela com as facturas emitidas, começando pelas recentes e também poderá tomar accões
    por cada uma delas ou personalizar a forma de apresentação
    através das opções abaixo</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Número</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Leitura</th>
                    <th>Valor</th>
                    <th>Em Dívida</th>
                    <th>Estado</th>
                    <th>Acções</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($invoices as $factura) :
                    echo "<tr id='{$factura->ID}'>";
                    echo "<td>{$factura->ID}</td>";
                    echo "<td>{$factura->Date}</td>";
                    echo "<td>{$factura->Nome}</td>";
                    echo "<td>{$factura->Consumo} m3</td>";
                    echo "<td>{$factura->Valor} MT</td>";
                    echo "<td>{$factura->Divida} MT</td>";
                    //LIMITACAO DE ACCOES CONSOANTE AO ESTADO DA FACTURA
                    switch ($factura->Estado) :
                        case 1:
                            echo '<td>Em dívida</td>
                                        <td class="text-center">
                                        <button title="Cancelar" onclick="cancel_invoice('.$factura->ID.')" class="btn btn-info btn-circle btn-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <a href="' . $router->route("admin.facturas.visualizar", ["id" => $factura->ID]) . '" class="btn btn-success btn-circle btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="' . $router->route("admin.facturas.imprimir", ["id" => $factura->ID]) . '" class="btn btn-dark btn-circle btn-sm">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        </td>
                                        </tr>
                                    ';
                            break;

                        case 2:
                            echo '<td>Paga</td>
                                    <td class="text-center">
                                        <a href="' . $router->route("admin.facturas.visualizar", ["id" => $factura->ID]) . '" class="btn btn-success btn-circle btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="' . $router->route("admin.facturas.imprimir", ["id" => $factura->ID]) . '" class="btn btn-dark btn-circle btn-sm">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                    </tr>
                                    ';
                            break;

                        case 3:
                            echo "<td>Cancelada</td>
                                        <td class='text-center'>
                                            -SEM ACÇÃO-
                                        </td>
                                        </tr>                                    
                                    ";
                            break;

                        case 4:
                            echo '<td>Atrasada</td>
                                        <td class="text-center">
                                            <a href="' . $router->route("admin.facturas.visualizar", ["id" => $factura->ID]) . '" class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="' . $router->route("admin.facturas.imprimir", ["id" => $factura->ID]) . '" class="btn btn-dark btn-circle btn-sm">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </td>
                                        </tr>
                                    ';
                            break;
                    endswitch;
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header pb-1">
        <h5 class="card-title text-primary p-0">Emissão de Factura</h5>
    </div>
    <div class="card-body">
        <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
            <button type="button" class="close" onclick="hide()">
                <span>&times;</span>
            </button>
        </div>
        <form class="user" id="form">
            <div class="form-group col-sm-5">
                <label for="Cliente" class="pl-2">Cliente</label>
                <select class="js-example-basic-single js-states form-control" name="Cliente" id="Cliente">
                    <option></option>
                    <?php foreach ($clients as $client) {
                        echo "<option value='{$client->ID}' id='cl{$client->ID}'>{$client->ID} - {$client->Nome}</option>";
                    } ?>
                </select>
            </div>
            <div class="form-group col-sm-5">
                <label for="Consumo" class="pl-2">Leitura Atual</label>
                <input type="text" name="Consumo" class="form-control form-control-user" onkeyup="somar()" id="Consumo"
                       placeholder="Digite aqui a leitura obtida do contador">
                <br>
                <div class="alert-success">&nbsp;O valor da factura é <span id="resultado">0 MT</span></div>
            </div>
            <div class="form-group col-sm-5">
                <label class="pl-2" for="Avisar">SMS de Aviso para o cliente &nbsp; &nbsp;</label>
                <label class="switch">
                    <input type="checkbox" id="Avisar" value="1" name="Avisar">
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="form-group col-sm-5">
                <div class="row-cols-2">
                    <button type="submit" id="submit" class="btn btn-success">Emitir <i class="fas fa-check-circle"></i>
                    </button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger">Cancelar <i class="fas fa-times-circle"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            placeholder: 'Selecione o cliente',
            allowClear: true
        });
    });

    //FUNCAO ON-THE-FLY PARA CALCULAR O PRECO DA FACTURA COM BASE NO CONSUMO DIGITADO
    function somar(){

        let x = document.getElementById("Consumo").value;
        let calculo = parseFloat(x) * parseFloat("<?= BUSINESS_MODEL["Price"] ?>");

        if(x <= <?= BUSINESS_MODEL["baseVolume"] ?>){
            calculo = "<?= BUSINESS_MODEL["basePrice"] ?>";
        }

        if (x === undefined){
            calculo = 0.00;
        }
        document.getElementById('resultado').innerHTML = calculo + " MT";
    }

    //HANDLER DO POST DE EMISSAO DE FACTURA
    let form = document.getElementById('form');
    const page = "<?= $router->route("admin.facturas.emitir")?>";
    let pageeditar = "<?= $router->route("admin.facturas.cancelar") ?>";
    let lastId = <?= $invoices[0]->ID ?>;
</script>
