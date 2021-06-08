<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title><?= $_ENV['LICENSED_TO'] ?> - Factura <?= $invoice->id ?></title>
    <link href="<?= assets('css/sb-admin-2.min.css') ?>" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,800;1,500;1,700&display=swap"
          rel="stylesheet">
    <style>
        body {
            font-family: "Montserrat";
            color: #000000;
            font-size: 16px;
        }
    </style>
</head>
<body onload="window.print()">
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <img src="<?= assets('img/SGFA.png') ?>" alt="" width="240px" height="80px">
            <h5 class="px-5 font-weight-bold pt-3"><?= $_ENV['LICENSED_TO'] ?></h5>
            <h5 class="px-5 font-weight-bold">NUIT: 123456789</h5>
        </div>
        <div class="col-auto">
            <p>Tel: +258 88 123 4567</p>
            <p>Morada: Av de SGFA, Maputo</p>
            <p>Email: SGFA@mail.com</p>
            <p>Website: www.<?= $_ENV['APP_HOST'] ?></p>
        </div>
    </div>
    <div class="mt-4" style="border: 2px solid #000;">
        <h2 class="text-center text-danger pt-2"><b>FACTURA N<sup>o</sup> <?= $invoice->id ?></b></h2>
    </div>
    <div class="row pt-5">
        <div class="col">
            <h4><b>Dados do Cliente</b></h4>
            <p><b>Nome:</b> <?= $client->name ?> <?= $client->surname ?></p>
            <p><b>Morada:</b> <?= $client->address ?></p>
            <p><b>Contacto:</b> +258 <?= $client->phone ?></p>
        </div>
        <div class="col-auto">
            <h4 class="text-right"><b>Cobranças anteriores</b></h4>
            <table class="table" style="border: 3px solid #000">
                <?php
                if (!empty($last_invoices)): ?>
                <thead style="line-height: 4px;border: 3px solid #000">
                <th><b>Data</b></th>
                <th><b>Valor</b></th>
                <th class="text-right"><b>Em débito</b></th>
                </thead>
                <tbody style="line-height: 3px">
                <?php  foreach ($last_invoices as $k => $last) :
                        echo "<tr>";
                        echo "<td>$last->date_added</td>";
                        echo "<td>" . number_format($last->amount, 2) . "MZN</td>";
                        echo "<td align='right'>" . number_format($last->debt, 2) . "MZN</td>";
                        echo "</tr>";
                    endforeach;
                else:
                    echo "<h6 class='text-center'>Sem registro de <br> cobranças anteriores</h6>";
                endif;
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="pt-4">
        <div style="border: 2px solid #000000;line-height: 2px;" class="mb-2">
            <div class="row text-center">
                <div class="col">
                    <h4><b>Leituras</b></h4>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <p><b>ANTERIOR</b></p>
                            <p><?= $last_invoices[0]->counter ?? $client->counter_initial ?> m<sup>3</sup></p>
                        </div>
                        <div class="col-auto">
                            <p><b>ACTUAL</b></p>
                            <p><?= $invoice->counter ?> m<sup>3</sup></p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <h4><b>Consumo</b></h4>
                    <p></p>
                    <p></p>
                    <p><em><?= $invoice->consumption ?> m<sup>3</sup></em></p>
                </div>
                <div class="col">
                    <h4><b>Período</b></h4>
                    <p></p>
                    <p><em><?= $last_invoices[0]->date_added ?? $invoice->date_added ?>
                            à <?= $invoice->date_added ?></em></p>
                </div>
                <div class="col">
                    <h4><b>Total em dívidas</b></h4>
                    <p></p>
                    <p><b><?= number_format($client->debts, 2, ",", ".") ?> MZN</b></p>
                </div>
            </div>
        </div>
        <table class="table table-borderless pt-2" style="font-size: 1.25rem">
            <thead style="border: 3px solid #000">
            <tr>
                <th scope="col">DESCRIÇÃO</th>
                <th class="text-right" scope="col">QUANTIDADE</th>
                <th class="text-right" scope="col">VALOR UNITÁRIO</th>
                <th class="text-right" scope="col">TOTAL</th>
            </tr>
            </thead>
            <tbody style="border: 3px solid #000;">
            <tr style="height: 160px;">
                <td><em>Água Potável</em></td>
                <td align="right">
                    <p><?= number_format($invoice->consumption, 2, ",", ".") ?> m<sup>3</sup></p>
                </td>
                <td align="right">
                    <p><?= $config->price_per_m3 ?> MZN</p>
                </td>
                <td align="right">
                    <b><?= number_format($invoice->amount, 2, ",", ".") ?> MZN</b>
                </td>
            </tr>
            </tbody>
            <tfoot style="border: 3px solid #000;">
            <tr>
                <td colspan="3"><b>VALOR TOTAL A PAGAR</b></td>
                <td align="right"><b><?= number_format(($invoice->amount + $invoice->fine), 2, ",", ".") ?> MZN</b></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="p-2" style="border: 2px solid #000000">
        <h5 class="text-dark"><b>Pagável via internet, depósito, transferências, celular ou outros meios para as
                contas:</b></h5>
        <ul>
            <?php
            foreach ($payment_methods as $method):
                echo "<li>$method->name</li>";
            endforeach;
            ?>
        </ul>
        <b class="text-danger">Após efectuar o pagamento, envie-nos o respectivo comprovativo para emissão de recibo</b>
        <b class="text-danger"><br>Se o pagamento ocorrer após a data limite indicada no canto inferior direito, será
            aplicada
            uma multa de <?= $config->fine ?> MZN</b>
    </div>

    <div class="row pt-4">
        <div class="col">
            <h5><b>Emissão:</b> <?= $invoice->date_added ?></h5>
        </div>
        <div class="col-auto">
            <h5><b>Data Limite:</b> <?= $invoice->expiry_date ?></h5>
        </div>
        <div class="col-12">
            <p class="text-center">Documento processado por computador, é válido mesmo sem assinatura e ou
                carimbo.</p>
        </div>
    </div>

</div>
</body>
</html>
