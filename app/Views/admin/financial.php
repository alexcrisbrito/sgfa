<?php $this->layout('admin::_template_'); ?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-gray-800"><i class="fa fa-money-check-alt"></i> Financeiro</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm">
             <i class="fas fa-file-chart-line"></i> <i class="fas fa-arrow-to-bottom"></i> Relatório mensal
        </a>
    </div>

    <p class="mb-4">Abaixo poderá fazer a avaliação do seu negócio, os lucros obtidos, gerir despesas e entre outros</p>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Lucro (Este mês)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats->profits, 2, ",", ".") ?> MZN
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x"></i>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Lucro Total (Este
                                ano)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format((array_sum(array_column($receipts, 'amount')) - array_sum(array_column($expenses, "amount"))), 2, ",", ".") ?>
                                MZN
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Despesas (Este mês)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats->expenses, 2, ",", ".") ?> MT
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Despesas (Este ano)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(array_sum(array_column($expenses, 'amount')), 2, ",", ".") ?> MT
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="row">
        <div class="col-xl-7 col-lg-7">

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Flutuação do Lucro</h6>
                    <span class="font-weight-bold text-danger">LUCRO MÉDIO ANUAL:
                    <?= number_format((array_sum($stats->chartArea) / 12), 2, ",", ".") ?> MZN </span>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Saldo das contas</h6>
                    <span class="font-weight-bold text-success">TOTAL:
                    <?= number_format((array_sum($stats->chartPieValues)), 2, ",", ".") ?> MZN </span>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-2 text-center small">
                        <p class="font-weight-bold">*Valores expressos em Meticais</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-7">
            <div class="card shadow mb-4">
                <div class="card-header pb-1">
                    <h5 class="card-title text-primary font-weight-bold p-0">Registro de Despesa</h5>
                </div>
                <div class="card-body">
                    <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                        <button type="button" class="close" onclick="hide()">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form class="user" onsubmit="post_expense(event)">
                        <div class="form-group">
                            <label for="Nome" class="pl-2">*Nome</label>
                            <input type="text" name="name" class="form-control" maxlength="255" id="Nome"
                                   placeholder="Digite aqui o nome da despesa" required>
                        </div>
                        <div class="form-group">
                            <label for="Celular" class="pl-2">*Valor</label>
                            <input type="text" name="amount" class="form-control" maxlength="10" id="Valor"
                                   placeholder="Digite aqui o valor da despesa" required>
                        </div>
                        <div class="form-group">
                            <label for="account_id" class="pl-2">*Selecione a conta</label>
                            <select class="form-control" name="account_id" id="account_id" required>
                                <?php
                                foreach ($payment_methods as $k => $v):
                                    echo '<option value="' . ($k + 1) . '">' . $v['name'] . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description" class="pl-2">Descrição</label>
                            <textarea name="description" class="form-control" placeholder="Digite aqui uma descrição"
                                      id="description" cols="30" rows="2" maxlength="150"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="row-cols-2">
                                <button type="submit" class="btn btn-success">Registrar <i
                                            class="fas fa-check-circle"></i>
                                </button>
                                &nbsp;
                                <button type="reset" class="btn btn-danger">Cancelar <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-5">
            <div class="card shadow mb-4">
                <div class="card-header pb-1">
                    <h5 class="card-title text-primary font-weight-bold p-0">Registro de Conta</h5>
                </div>
                <div class="card-body">
                    <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                        <button type="button" class="close" onclick="hide()">
                            <span>&times;</span>
                        </button>
                    </div>
                    <p class="font-weight-bold">As contas servem como meios de pagamentos de todas as facturas pelos
                        clientes e despesas do negócio,
                        cada conta tem um saldo para saber exatamente quanto provem do negócio.</p>
                    <form class="user" onsubmit="post_account(event)">
                        <div class="form-group">
                            <label for="name" class="pl-2">Nome Completo</label>
                            <input type="text" name="name" class="form-control" maxlength="150" id="name"
                                   placeholder="Ex: Millennium BIM - 83228332..." required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="pl-2">Nome Curto</label>
                            <input type="text" name="short_name" class="form-control" maxlength="10" id="name"
                                   placeholder="Ex: Mbim 1" required>
                        </div>
                        <div class="form-group">
                            <label for="balance" class="pl-2">Saldo inicial</label>
                            <input type="number" name="balance" class="form-control"
                                   id="balance" placeholder="0.00 MT" value="0.00" min="0.00" required>
                        </div>
                        <div class="form-group">
                            <div class="row-cols-2">
                                <button type="submit" class="btn btn-success">Criar <i class="fas fa-check-circle"></i>
                                </button>
                                &nbsp;
                                <button type="reset" class="btn btn-danger">Cancelar <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header pb-1">
            <h5 class="card-title text-primary font-weight-bold p-0">Lista de Despesas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Conta de Pagamento</th>
                        <th>Data</th>
                        <th>Acções</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($expenses as $expense) {
                        echo "<tr id='{$expense['id']}'>";
                        echo "<td>{$expense['name']}</td>";
                        echo "<td>" . number_format($expense['amount'], 2, ",", ".") . " MZN</td>";
                        echo "<td>{$expense['paid_via']}</td>";
                        echo "<td>{$expense['date_added']}</td>";
                        echo '<td class="text-center">
                              <button onclick="delete_expense(' . $expense['id'] . ')" class="btn btn-danger">
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

<?php $this->start('scripts'); ?>
    <script>
        let unitArea = " MZN";
        let unitPie = " MZN"
        let dataArea = [<?= implode(",", $stats->chartArea) ?>];
        let dataPie = [<?= implode(",", $stats->chartPieValues) ?>];
        let labelspie = [<?= $stats->chartPieLabels ?>];
        let ColorsPie = ['#ea4f30', '#4e73df', '#f0f0f0', '#1cc88a'];
        let HoverPie = ['#ff231c', '#2e59d9', "#ffffff", '#17a673'];
        let last_expense_id = <?= $expenses[0]->id ?? 0 ?>;
    </script>

    <script src="<?= assets('vendor/chart.js/Chart.min.js') ?>"></script>
    <script src="<?= assets('js/financial.js') ?>"></script>
    <script src="<?= assets('js/demo/chart-area-demo.js') ?>"></script>
    <script src="<?= assets('js/demo/chart-pie-demo.js') ?>"></script>

<?php $this->end(); ?>