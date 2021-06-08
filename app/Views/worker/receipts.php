<?php $this->layout("worker::_template_") ?>
    <h1 class="h3 mb-2 font-weight-bolder text-gray-800"><i class="fa fa-receipt"></i> Recibos</h1>
    <p class="mb-4">Abaixo sita uma tabela com os recibos emitidos, começando pelos recentes e também poderá tomar accões
        por cada uma delas ou personalizar a forma de apresentação
        através das opções abaixo</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Data</th>
                        <th>Factura Referente</th>
                        <th>Valor Pago</th>
                        <th>Meio de Pagamento</th>
                        <th>Acções</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data as $receipt) :
                        echo "<tr><td>{$receipt['id']}</td>";
                        echo "<td>{$receipt['date_added']}</td>";
                        echo "<td>{$receipt['invoice']['id']} - {$receipt['client']['name']} {$receipt['client']['surname']}</td>";
                        echo "<td>".number_format($receipt['amount'], 2, ",", ".")." MT</td>";
                        echo "<td>{$receipt['paid_via']['name']}</td>";
                        echo '<td class="text-center">
                        <a target="_blank" href="' . $router->route("receipt.print", ["id" => $receipt['id']]) . '" class="btn btn-dark">
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
            <h5 class="card-title text-primary font-weight-bolder p-0">Emissão de Recibo</h5>
        </div>
        <div class="card-body">
            <form class="user" onsubmit="post_receipt(event)">
                <div class="form-group col-sm-9">
                    <label for="invoice_id" class="pl-2">Factura Referente</label>
                    <select class="js-example-basic-single js-states form-control"
                            onchange="select_invoice(this)" oninput="select_invoice(this)"
                            onselect="select_invoice(this)" name="invoice_id" id="invoice_id">
                        <option></option>
                        <?php
                        foreach ($invoices as $invoice) :
                            if (in_array($invoice['status'], [1,4,5]))
                                echo '<option value="'.$invoice['id'].'">'.$invoice['id'].' - '.$invoice['client']['name'].' '.$invoice['client']['surname'].' -> '.$invoice['debt'].' MT</option>';
                        endforeach;
                        ?>
                    </select>
                </div>
                <div class="form-group col-sm-9">
                    <label for="amount" class="pl-2">Valor Pago</label>
                    <input type="text" name="amount" class="form-control" id="amount"
                           placeholder="Digite aqui o valor pago pelo cliente">
                </div>
                <div class="form-group col-sm-9">
                    <label for="paid_via" class="pl-2">Conta de Pagamento</label>
                    <select name="paid_via" class="form-control"
                            onchange="select_paid_via(this)" oninput="select_paid_via(this)"
                            onselect="select_paid_via(this)" id="paid_via" required>
                        <option value="">Selecione a conta</option>
                        <?php
                        foreach ($_SESSION['accounts'] as $k => $v) :
                            echo '<option value="'.($k+1).'">'.$v['name'].'</option>';
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

<?php $this->start('scripts') ?>
    <script src="<?= assets('js/receipts.js') ?>" type="text/javascript"></script>
    <script>let last_receipt_id = <?= $data[0]['id'] ?? 0 ?></script>
<?php $this->end() ?>