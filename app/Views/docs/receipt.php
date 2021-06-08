<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title><?= $_ENV['LICENSED_TO'] ?> - Recibo <?= $receipt->id ?></title>
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
        <h1 class="text-center text-danger pt-2"><b>RECIBO N<sup>o</sup> <?= $receipt->id ?></b></h1>
    </div>

    <div class="row pt-5">
        <div class="col">
            <h4><b>Dados do Cliente</b></h4>
            <h5><b>Nome:</b> <?= $client->name ?></h5>
            <h5><b>Contacto:</b> <?= $client->phone ?></h5>
            <h5><b>Morada:</b> <?= $client->address ?></h5>
        </div>
        <div class="col-auto text-right">
            <h4 class="font-weight-bold">Factura referente</h4>
            <h5><b>Número:</b> <?= $invoice->id ?></h5>
            <h5><b>Valor:</b> <?= number_format($invoice->amount, 2, ",", ".") ?> MZN</h5>
            <h5><b>Leitura:</b> <?= $invoice->counter ?> m3</h5>
        </div>
    </div>


    <div class="pt-2">
        <table class="table" style="font-size: 1.25rem;border-left: 3px; border-right: 3px" >
            <thead style="border: 3px solid #000">
                <tr>
                    <th>Descrição</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody style="border: 3px solid #000">
                <tr style="height: 200px;">
                    <td><em>Fornecimento de Água Potável</em></td>
                    <td align="right"><b><?= number_format($receipt->amount, 2, ",", ".") ?> MZN</b></td>
                </tr>
            </tbody>
            <tfoot style="border: 3px solid #000">
                <tr>
                    <td><b>TOTAL PAGO</b></td>
                    <td align="right"><b><?= number_format($receipt->amount, 2, ",", ".") ?> MZN</b></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="col-auto p-3" style="border: 3px solid #000">
        <h4 class="text-dark"><b>Pago via</b>: <u><em><?= $receipt->paid_via ?></em></u></h4>
    </div>

    <h5 class="text-right py-2"><b>Data:</b> <?= $receipt->date_added ?></h5>

    <footer class="text-center pt-4">
        <p class="text-muted">Documento processado por computador, é válido mesmo sem assinatura e ou carimbo.</p>
    </footer>

</div>
</body>
</html>
