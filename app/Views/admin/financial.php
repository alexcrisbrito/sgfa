<?php $this->layout('admin::_template_');?>

<h1 class="h3 mb-2 text-gray-800">Financeiro</h1>
<p class="mb-4">Abaixo poderá fazer a avaliação do seu negócio, gerir despesas e os lucros obtidos</p>

<div class="row">
    <div class="col-xl-8 col-lg-7">

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Flutuação do lucro líquido</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Despesas (Últimas 3)</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-2 text-center small">
                    <p>Valores expressos em meticais MZN</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        <div class="card shadow mb-4">
            <div class="card-header pb-1">
                <h5 class="card-title text-primary p-0">Registro de Despesa</h5>
            </div>
            <div class="card-body">
                <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                    <button type="button" class="close" onclick="hide()">
                        <span>&times;</span>
                    </button>
                </div>
                <form class="user" id="form">
                    <div class="form-group">
                        <label for="Nome" class="pl-2">Nome</label>
                        <input type="text" name="Nome" class="form-control form-control-user" maxlength="255" id="Nome"
                               placeholder="Digite aqui o nome da despesa">
                    </div>
                    <div class="form-group">
                        <label for="Celular" class="pl-2">Valor</label>
                        <input type="text" name="Valor" class="form-control form-control-user" maxlength="10" id="Valor"
                               placeholder="Digite aqui o valor da despesa">
                    </div>
                    <div class="form-group">
                        <label for="Meio" class="pl-2">Meio de Pagamento</label>
                        <select class="form-control" name="Meio">
                            <?php
                                foreach (BUSINESS_MODEL["money_acc"] as $item){
                                    echo '<option value="'.$item.'">'.$item.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row-cols-2">
                            <button type="submit" class="btn btn-success">Registrar <i class="fas fa-check-circle"></i></button>
                            &nbsp;
                            <button type="reset" class="btn btn-danger">Cancelar <i class="fas fa-times-circle"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-7">
        <div class="card shadow mb-4">
            <div class="card-header pb-1">
                <h5 class="card-title text-primary p-0">Despesas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th>Meio de Pagamento</th>
                            <th>Data</th>
                            <th>Acções</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($dados as $despesa){
                                echo "<tr id='{$despesa->ID}'>";
                                echo "<td>{$despesa->Nome}</td>";
                                echo "<td>".number_format($despesa->Valor,2,",",".")." MT</td>";
                                echo "<td>{$despesa->Meio}</td>";
                                echo "<td>{$despesa->Date}</td>";
                                echo '<td class="text-center">
                                        <button onclick="delete_expense('.$despesa->ID.')" class="btn btn-danger btn-circle btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                      </td>';
                                echo "</tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="<?= assets('vendor/chart.js/Chart.min.js')?>"></script>
<script>
    let dataArea = [<?= implode(",",$stats->ChartArea) ?>];
    let unitArea = "MT";
    let form = document.getElementById('form');
    let page = "<?= $router->route("admin.financeiro.despesas.emitir")?>";
    let dataPie = [<?= $stats->ChartPie[0].",".$stats->ChartPie[1].",".$stats->ChartPie[2] ?>];
    let labelspie = ['<?= $stats->ChartPie[3]?>','<?=$stats->ChartPie[4]?>','<?= $stats->ChartPie[5] ?>'];
    let ColorsPie = ['#4e73df', '#1cc88a',"#c0c0c0"];
    let HoverPie = ['#2e59d9', '#17a673','#c3c3c3'];
    let pagedeleteex = "<?= $router->route("admin.financeiro.despesas.apagar") ?>";
    let lastId = <?= $dados[0]->ID ?? 0 ?>;
</script>

<script src="<?= assets('js/demo/chart-area-demo.js')?>"></script>
<script src="<?= assets('js/demo/chart-pie-demo.js')?>"></script>

