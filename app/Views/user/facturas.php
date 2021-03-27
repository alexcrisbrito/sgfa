<?php $v->layout("user::_template_") ?>
<h1 class="h3 mb-2 text-gray-800">Facturas</h1>
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
                    <th>Leitura</th>
                    <th>Valor</th>
                    <th>Multa</th>
                    <th>Em Dívida</th>
                    <th>Estado</th>
                    <th>Acções</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dados as $factura) {
                    echo "<tr>";
                    echo "<td>{$factura->ID}</td>";
                    echo "<td>{$factura->Date}</td>";
                    echo "<td>{$factura->Consumo} m3</td>";
                    echo "<td>{$factura->Valor} MT</td>";
                    echo "<td>{$factura->Multa} MT</td>";
                    echo "<td>{$factura->Divida} MT</td>";
                    //LIMITACAO DE ACCOES CONSOANTE AO ESTADO DA FACTURA
                    switch ($factura->Estado) {
                        case 1:
                            echo '<td>Em dívida</td>
                                    <td class="text-center">
                                    <a target="_blank" href="' . $router->route("user.facturas.imprimir", ["id" => $factura->ID]) . '" class="btn btn-dark btn-circle btn-sm">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    </td>
                                    </tr>
                                ';
                            break;

                        case 2:
                            echo '<td>Paga</td>
                                <td class="text-center">
                                <a target="_blank" href="' . $router->route("user.facturas.imprimir", ["id" => $factura->ID]) . '" class="btn btn-dark btn-circle btn-sm">
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
                                    <a target="_blank" href="' . $router->route("user.facturas.imprimir", ["id" => $factura->ID]) . '" class="btn btn-dark btn-circle btn-sm">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    </td>
                                    </tr>
                                ';
                            break;
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>