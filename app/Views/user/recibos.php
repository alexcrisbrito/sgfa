<?php $this->layout("user::_template_") ?>
<h1 class="h3 mb-2 text-gray-800">Recibos</h1>
<p class="mb-4">Abaixo sita uma tabela com os recibos emitidos, começando pelos mais recentes, poderá baixar, imprimir ou personalizar a visualização
    através das opções abaixo</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Data</th>
                    <th>Valor Pago</th>
                    <th>Meio de Pagamento</th>
                    <th>Acções</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dados as $recibo) {
                    echo "<td>{$recibo->Date}</td>";
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
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>