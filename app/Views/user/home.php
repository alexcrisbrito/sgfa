<?php $this->layout("user::_template_") ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 font-weight-bold text-gray-800"><i class="fa fa-home"></i> Bem vindo, <?= $client->name ?> !</h1>
    <a href="<?= $router->route("client.historic", ["id" => $client->id]) ?>" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow">
        <i class="fa fa-chart-area text-white"></i> Histórico de consumo
    </a>
</div>

<div class="row">

    <div class="col-xl-4 col-md-4 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Última leitura no contador</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($stats->counter ,2,",",".") ?> m3</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tachometer-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Último Consumo</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats->monthConsumption,2,",",".") ?> m3</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tint fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total de Dívidas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats->debts,2,",",".") ?> MT</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row">

    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Gráfico de Leituras</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="<?= assets('vendor/chart.js/Chart.min.js')?>"></script>
<script>
    let dataArea = [<?= implode(",", $stats->chart) ?>];
    let unitArea = "m3";
</script>
<script src="<?= assets('js/demo/chart-area-demo.js')?>"></script>
