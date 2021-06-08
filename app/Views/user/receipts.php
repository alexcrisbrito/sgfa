<?php $this->layout("user::_template_") ?>
<h1 class="h3 mb-2 font-weight-bold text-gray-800"><i class="fa fa-receipt"></i> Recibos</h1>
<p class="mb-4">Abaixo sita uma tabela com os recibos emitidos, começando pelos mais recentes, poderá baixar, imprimir ou personalizar a visualização
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
                    <th>Valor Pago</th>
                    <th>Meio de Pagamento</th>
                    <th>Acções</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($receipts as $receipt) {
                    echo "<td>{$receipt['id']}</td>";
                    echo "<td>{$receipt['date_added']}</td>";
                    echo "<td>".number_format($receipt['amount'], 2, ",", ".")." MT</td>";
                    echo "<td>{$receipt['paid_via']}</td>";
                    echo '<td class="text-center">
                        <a target="_blank" href="' . $router->route("receipt.print", ["id" => $receipt['id']]) . '" class="btn btn-dark btn-md">
                            <i class="fas fa-print"></i>
                        </a>
                        </td>
                        </tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>