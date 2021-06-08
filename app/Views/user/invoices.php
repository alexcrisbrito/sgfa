<?php $this->layout("user::_template_") ?>
<h1 class="h3 mb-2 font-weight-bold text-gray-800"><i class="fa fa-file-invoice-dollar"></i> Facturas</h1>
<p class="mb-4">Abaixo sita uma tabela com as facturas emitidas começando pelas mais recentes, poderá imprimir, visualizar ou personalizar a forma de apresentação
    através das opções abaixo</p>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Data</th>
                    <th>Contador</th>
                    <th>Consumo</th>
                    <th>Valor</th>
                    <th>Multa</th>
                    <th>Em Dívida</th>
                    <th>Estado</th>
                    <th>Acção</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($invoices as $invoice) :
                    echo "<tr>";
                    echo "<td>$invoice->id</td>";
                    echo "<td>$invoice->date_added</td>";
                    echo "<td>".number_format($invoice->counter, 2, ",", ".") ."m3</td>";
                    echo "<td>".number_format($invoice->consumption, 2, ",", ".") ."m3</td>";
                    echo "<td>".number_format($invoice->amount, 2, ",", ".") ."MT</td>";
                    echo "<td>".number_format($invoice->fine, 2, ",", ".") ."MT</td>";
                    echo "<td>".number_format($invoice->debt, 2, ",", ".") ."MT</td>";
                    //LIMITACAO DE ACCOES CONSOANTE AO ESTADO DA FACTURA
                    switch ($invoice->status) :
                        case 1:
                            echo '<td>Em dívida</td>
                                    <td class="text-center">
                                    <a target="_blank" href="' . $router->route("invoice.print", ["id" => $invoice->id]) . '" class="btn btn-dark btn-md">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    </td>
                                    </tr>
                                ';
                            break;

                        case 2:
                            echo '<td>Paga</td>
                                <td class="text-center">
                                <a target="_blank" href="' . $router->route("invoice.print", ["id" => $invoice->id]) . '" class="btn btn-dark btn-md">
                                    <i class="fas fa-print"></i>
                                </a>
                                </td>
                                </tr>
                                ';
                            break;

                        case 3:
                            echo "<td>Cancelada</td>
                                    <td class='text-center'>
                                     - SEM ACÇÕES -
                                    </td>
                                    </tr>                                    
                                ";
                            break;

                        case 4:
                            echo '<td>Atrasada</td>
                                    <td class="text-center">
                                    <a target="_blank" href="' . $router->route("invoice.print", ["id" => $invoice->id]) . '" class="btn btn-dark btn-md">
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