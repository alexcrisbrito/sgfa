<?php $this->layout("worker::_template_") ?>
<h1 class="h3 mb-2 text-gray-800">Recibos</h1>
<p class="mb-4">Abaixo sita uma tabela com os recibos emitidos, começando pelos recentes e também poderá tomar accões
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
                    <th>Factura Referente</th>
                    <th>Valor Pago</th>
                    <th>Meio de Pagamento</th>
                    <th>Acções</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($dados as $recibo) :
                    echo "<tr><td>{$recibo->ID}</td>";
                    echo "<td>{$recibo->Date}</td>";
                    echo "<td>{$recibo->Factura} - {$recibo->Nome}</td>";
                    echo "<td>{$recibo->Valor} MT</td>";
                    echo "<td>{$recibo->Meio}</td>";
                    echo '<td class="text-center">
                        <a target="_blank" href="' . $router->route("admin.recibos.visualizar", ["id" => $recibo->ID]) . '" class="btn btn-info btn-circle btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a target="_blank" href="' . $router->route("admin.recibos.imprimir", ["id" => $recibo->ID]) . '" class="btn btn-dark btn-circle btn-sm">
                            <i class="fas fa-print"></i>
                        </a>
                        </td>
                        </tr>';
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header pb-1">
        <h5 class="card-title text-primary p-0">Emissão de Recibo</h5>
    </div>
    <div class="card-body">
        <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
            <button type="button" class="close" onclick="hide()">
                <span>&times;</span>
            </button>
        </div>
        <form class="user" id="form">
            <div class="form-group col-sm-5">
                <label for="Factura" class="pl-2">Factura Referente</label>
                <select class="js-example-basic-single js-states form-control" name="Factura" id="Factura">
                    <option></option>
                    <?php
                        foreach ($invoices as $invoice):
                            echo '<option value="'.$invoice->ID.'">'.$invoice->invoice.' -> '.$invoice->Divida.' MT</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="form-group col-sm-5">
                <label for="Valor" class="pl-2">Valor Pago</label>
                <input type="text" name="Valor" class="form-control form-control-user" id="Valor"
                       placeholder="Digite aqui o valor pago pelo cliente">
            </div>
            <div class="form-group col-sm-5">
                <label for="Meio" class="pl-2">Meio de Pagamento</label>
                <select name="Meio" class="form-control" id="Meio" required>
                    <option value="">Selecione o meio</option>
                    <?php
                    foreach (BUSINESS_MODEL["money_acc"] as $acc) :
                        ?>
                        <option value="<?= $acc ?>"><?= $acc ?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="form-group col-sm-5">
                <div class="row-cols-2">
                    <button type="submit" class="btn btn-success">Emitir <i class="fas fa-check-circle"></i></button>
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
            placeholder: 'Selecione a fatura',
            allowClear: true
        });
    });

    let form = document.getElementById('form');
    let page = "<?= $router->route("admin.recibos.emitir")?>";
    let lastId = <?= $dados[0]->ID ?>;
</script>
