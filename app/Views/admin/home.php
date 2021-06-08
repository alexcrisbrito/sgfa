<?php $this->layout("admin::_template_")?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 font-weight-bold text-gray-800"><i class="fa fa-chart-area"></i> Painel de Controle</h1>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Lucro Líquido (Este mês)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats->profits,2,",",".") ?> MT</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Clientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats->clients ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Consumo (Este mês)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats->total_consumption,2,",",".") ?> m3</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tint fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Despesas (Este mês)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats->expenses,2,",",".") ?> MT</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tasks fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->

<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Flutuação do Lucro Bruto</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Consumo de Água</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-3 text-center small">
                    <span>
                        <?php if($stats->ChartPie[0] > $stats->ChartPie[1]): ?>
                            O consumo deste mês é menor <i class="fas fa-arrow-alt-circle-down text-danger"></i>
                        <?php elseif($stats->ChartPie[0] < $stats->ChartPie[1]):?>
                            O consumo deste mês é maior <i class="fas fa-arrow-alt-circle-up text-success"></i>
                        <?php else:?>
                            O consumo é igual <i class="fas fa-arrow-alt-circle-right text-success"></i>
                        <?php endif; ?>
                        <br>
                        Em metros cúbicos (m3)
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= assets('vendor/chart.js/Chart.min.js')?>"></script>
<script>
    let dataArea = [<?= implode(",",$stats->ChartArea) ?>];
    let unitArea = "MZN";
    let unitPie = " m3"
    let dataPie = [<?= $stats->ChartPie[0].",".$stats->ChartPie[1] ?>];
    let labelspie = ["<?= $stats->ChartPie[2]?>","<?= $stats->ChartPie[3] ?>"];
    let ColorsPie = ['#4e73df', '#1cc88a'];
    let HoverPie = ['#2e59d9', '#17a673'];
</script>
<script src="<?= assets('js/demo/chart-area-demo.js')?>"></script>
<script src="<?= assets('js/demo/chart-pie-demo.js')?>"></script>