<?php $this->layout("worker::_template_") ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 font-weight-bolder text-gray-800"><i class="fa fa-file-invoice"></i> Facturas</h1>
    <p class="mb-4 text-dark">Abaixo sita uma tabela com as facturas emitidas, começando pelas mais recentes e também poderá tomar accões
        por cada uma delas ou personalizar a forma de apresentação
        através das opções abaixo</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Contador</th>
                        <th>Consumo</th>
                        <th>Valor</th>
                        <th>Em Dívida</th>
                        <th>Multa</th>
                        <th>Estado</th>
                        <th>Acções</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data as $invoice) :
                        echo "<tr id='inv{$invoice['id']}'>";
                        echo "<td>{$invoice['id']}</td>";
                        echo "<td>{$invoice['date_added']}</td>";
                        echo "<td>{$invoice['client']['id']} - {$invoice['client']['name']}</td>";
                        echo "<td>{$invoice['counter']} m3</td>";
                        echo "<td>".number_format($invoice['consumption'], 2,",", ".")." m3</td>";
                        echo "<td>".number_format($invoice['amount'], 2,",", ".") ." MT</td>";
                        echo "<td>".number_format($invoice['debt'], 2,",", ".") ." MT</td>";
                        echo "<td>".number_format($invoice['fine'], 2,",", ".")." MT</td>";

                        switch ($invoice['status']) :
                            case 1:
                                echo '<td>Em dívida</td>
                                        <td class="text-center">
                                        <button title="Cancelar" onclick="cancel_invoice('.$invoice['id'].')" class="btn btn-danger btn-sm">
                                            <i class="fa fa-times-circle"></i>
                                        </button>
                                        <a href="' . $router->route("invoice.print", ["id" => $invoice['id']]) . '" class="btn btn-dark btn-sm">
                                            <i class="fas fa-print"></i>
                                        </a>
                                      </td>
                                      </tr>
                                    ';
                                break;

                            case 2:
                                echo '<td>Paga</td>
                                    <td class="text-center">
                                    <a href="'. $router->route("invoice.print", ["id" => $invoice['id']]) . '" class="btn btn-dark btn-sm">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    </td>
                                    </tr>
                                    ';
                                break;

                            case 3:
                                echo '<td>Cancelada</td>
                                        <td class="text-center">
                                        <button title="Reactivar" onclick="reactivate_invoice('.$invoice['id'].')" class="btn btn-success btn-sm">
                                            <i class="fa fa-check-circle"></i>
                                        </button>
                                        </td>
                                        </tr>                                    
                                    ';
                                break;

                            case 4:
                                echo '<td>Atrasada</td>
                                        <td class="text-center">
                                        <button title="Cancelar" onclick="cancel_invoice('.$invoice['id'].')" class="btn btn-danger btn-sm">
                                            <i class="fa fa-times-circle"></i>
                                        </button>
                                        <a title="Baixar" href="' . $router->route("invoice.print", ["id" => $invoice['id']]) . '" class="btn btn-dark btn-sm">
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
            <h5 class="card-title text-primary font-weight-bold p-0">Emissão de Factura</h5>
        </div>
        <div class="card-body">
            <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
                <button type="button" class="close" onclick="hide()">
                    <span>&times;</span>
                </button>
            </div>
            <form class="user" onsubmit="post_invoice(event)">
                <div class="form-group col-sm-5">
                    <label for="client" class="pl-2">Cliente</label>
                    <select class="js-example-basic-single js-states form-control"
                            oninput="set_min_counter_value(this.value)" onselect="set_min_counter_value(this.value)"
                            onchange=set_min_counter_value(this.value)" name="client_id" id="client" required>
                        <option></option>
                        <?php
                        foreach ($clients as $client) :
                            echo "<option value='{$client['id']}' id='cl{$client['id']}'>{$client['id']} - {$client['name']} {$client['surname']}</option>";
                        endforeach;
                        ?>
                    </select>
                </div>

                <div class="form-group col-sm-5">
                    <label for="counter" class="pl-2">Leitura</label>
                    <input type="number" min="0" step="1.0" name="counter" class="form-control" onkeyup="calculate()" id="counter"
                           placeholder="Digite aqui a leitura obtida no contador" required>
                    <br>
                    <div class="alert-light rounded px-1">&nbsp;O consumo foi de <span id="consumption">0 m<sup>3</sup> </span></div>
                    <div class="alert-light rounded px-1">&nbsp;O valor da factura é <span id="amount">0 MZN</span></div>
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

<?php $this->start("scripts") ?>
    <script>
        const previous_counter_readings = <?= $previous_counter_readings ?>;
        let last_invoice_id = <?= $data[0]['id'] ?? 0 ?>;
        const price_per_m3 = <?= $_SESSION['config']['price_per_m3'] ?>;
    </script>
    <script src="<?= assets('js/invoices.js') ?>" type="text/javascript"></script>
<?php $this->end() ?>