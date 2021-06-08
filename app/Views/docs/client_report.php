<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <link href="<?= assets('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= assets('css/sb-admin-2.min.css') ?>" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,800;1,500;1,700&display=swap"
          rel="stylesheet">
    <title><?= $_ENV['LICENSED_TO'] ?> - Relatório do Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Montserrat";
        }
    </style>
</head>
<body>
<div class="container text-dark">
    <div class="row">
        <div class="col">
            <img src="<?= assets('img/SGFA.png') ?>" alt="" width="240px" height="80px">
            <h5 class="px-5 pt-3"><?= $_ENV['LICENSED_TO'] ?></h5>
            <h5 class="px-5">NUIT: 123456789</h5>
        </div>
        <div class="col-auto">
            <p>Tel: +258 88 123 4567</p>
            <p>Morada: Av de SGFA, Maputo</p>
            <p>Email: SGFA@mail.com</p>
            <p>Website: www.<?= $_ENV['APP_HOST'] ?></p>
        </div>
    </div>
    <br>
    <h3 class="font-weight-bold text-center text-danger pt-2"><i class="fa fa-file-chart-line"></i> Relatório do Cliente</h3>
    <br>
    <div class="row">
        <div class="col">
            <h5><span class="font-weight-bold">Nome:</span> <?= $client->name ?> <?= $client->surname ?></h5>
            <h5><span class="font-weight-bold">Celular:</span> <?= $client->phone ?></h5>
            <h5><span class="font-weight-bold">Morada:</span> <?= $client->address ?></h5>
        </div>
        <div class="col-auto">
            <h5><span class="font-weight-bold">Data:</span> <?= date('d-m-Y') ?></h5>
            <h5><span class="font-weight-bold">Período:</span> <?= $invoices[array_key_last($invoices)]->date_added ?? date('d-m-Y') ?> à
                <?= $invoices[0]->date_added ?? date('d-m-Y') ?></h5>
            <h5><span class="font-weight-bold">Contador:</span> AZ392304873</h5>
        </div>
    </div>

    <h4 class="font-weight-bold text-danger pt-5"><em><i class="fa fa-chart-line"></i> Dados complementares</em></h4>
    <div class="row pt-3">
        <div class="col">
            <h6><span class="font-weight-bold text-info">Última leitura:</span>
                <?= number_format($invoices[0]->counter ?? 0, 2, ",", ".") ?> m3
            </h6>
            <h6><span class="font-weight-bold text-info">Último Consumo:</span>
                <?= number_format($invoices[0]->consumption ?? 0, 2, ",", ".") ?> m3
            </h6>
            <h6><span class="font-weight-bold text-info">Último Pagamento:</span>
                <?= number_format($receipts[0]->amount ?? 0, 2, ",", ".")  ?> MZN
            </h6>
        </div>
        <div class="col-auto" style="border: 3px #000;">
            <h6><span class="font-weight-bold text-danger">Dívidas:</span>
                <?= number_format(array_sum(array_column($invoices, 'debt')), 2, ",", ".") ?> MZN
            </h6>
            <h6><span class="font-weight-bold text-danger">Consumo total:</span>
                <?= number_format(array_sum(array_column($invoices, 'consumption')), 2, ",", ".") ?> m3
            </h6>
            <h6><span class="font-weight-bold text-danger">Total Pago:</span>
                <?= number_format(array_sum(array_column($receipts, 'amount')), 2, ",", ".") ?> MZN
            </h6>
        </div>
    </div>

    <?php if (!empty($invoices)) : ?>
    <p class="pt-5">Descrimina-se na tabela abaixo todas as facturas já registadas em nome deste cliente:</p>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="bg-gradient-dark text-white">
            <tr>
                <th>#</th>
                <th>Data</th>
                <th>Leitura</th>
                <th>Consumo</th>
                <th>Valor</th>
                <th>Em Dívida</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($invoices as $invoice) :
                echo "<tr>";
                echo "<td>$invoice->id</td>";
                echo "<td>$invoice->date_added</td>";
                echo "<td>$invoice->counter m3</td>";
                echo "<td>".number_format($invoice->consumption, 2,",", ".")." m3</td>";
                echo "<td>".number_format($invoice->amount, 2,",", ".") ." MZN</td>";
                echo "<td>".number_format($invoice->debt, 2,",", ".") ." MZN</td>";
                switch ($invoice->status) :
                    case 1:
                        echo '<td>Em dívida</td>';
                        break;

                    case 2:
                        echo '<td>Paga</td>';
                        break;

                    case 3:
                        echo '<td>Cancelada</td>';
                        break;

                    case 4:
                        echo '<td>Atrasada</td>';
                        break;
                endswitch;
                echo "<tr>";
            endforeach;
            ?>
            </tbody>
        </table>
    </div>
    <p class="text-center pt-2">--------------------------------- Fim das facturas ---------------------------------</p>
    <p>Total de Registros: <?= count($invoices) ?></p>
    <?php else: ?>
        <h3 class="text-center">SEM FACTURAS REGISTRADAS</h3>
    <?php endif; ?>
    <hr>
    <p class="text-center">Documento processado por computador</p>


</div>
</body>
</html>
