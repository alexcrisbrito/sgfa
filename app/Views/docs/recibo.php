<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Águas AZ - Recibo <?= $recibo->ID ?></title>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat';
        }

        #invoice {
            padding: 0px;
        }

        .invoice {
            position: relative;
            background-color: #FFF;
            min-height: 680px;
            padding: 15px
        }

        .invoice header {
            padding: 10px 0;
            margin-bottom: 0px;
            border-bottom: 1px solid #3989c6
        }

        .invoice .company-details {
            text-align: right
        }

        .invoice .company-details .name {
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .contacts {
            margin-bottom: 5px
        }

        .invoice .invoice-to {
            text-align: left
        }

        .invoice .invoice-to .to {
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .invoice-details {
            text-align: right
        }

        .invoice .invoice-details .invoice-id {
            margin-top: 0;
            color: #3989c6
        }

        .invoice main {
            padding-bottom: 5px
        }

        .invoice main .thanks {
            margin-top: -50px;
            font-size: 2em;
            margin-bottom: 10px
        }

        .invoice main .notices {
            padding-left: 6px;
            border-left: 6px solid #3989c6
        }

        .invoice main .notices .notice {
            font-size: 1.2em
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px
        }

        .invoice table td, .invoice table th {
            padding: 15px;
            background: #eee;
            border-bottom: 1px solid #fff
        }

        .invoice table th {
            white-space: nowrap;
            font-weight: 400;
            font-size: 16px
        }

        .invoice table td h3 {
            margin: 0;
            font-weight: 400;
            color: #3989c6;
            font-size: 1.2em
        }

        .invoice table .qty, .invoice table .total, .invoice table .unit {
            text-align: right;
            font-size: 1.2em
        }

        .invoice table .no {
            color: #fff;
            font-size: 1.6em;
            background: #3989c6
        }

        .invoice table .unit {
            background: #ddd
        }

        .invoice table .total {
            background: #3989c6;
            color: #fff
        }

        .invoice table tbody tr:last-child td {
            border: none
        }

        .invoice table tfoot td {
            background: 0 0;
            border-bottom: none;
            white-space: nowrap;
            text-align: right;
            padding: 10px 20px;
            font-size: 1.2em;
            border-top: 1px solid #aaa
        }

        .invoice table tfoot tr:first-child td {
            border-top: none
        }

        .invoice table tfoot tr:last-child td {
            color: #3989c6;
            font-size: 1.4em;
            border-top: 1px solid #3989c6
        }

        .invoice table tfoot tr td:first-child {
            border: none
        }

        .invoice footer {
            width: 100%;
            text-align: center;
            color: #777;
            border-top: 1px solid #aaa;
            padding: 8px 0
        }

        @media print {
            .invoice {
                font-size: 11px !important;
                overflow: hidden !important
            }

            .invoice footer {
                position: absolute;
                bottom: 10px;
                page-break-after: always
            }

            .invoice > div:last-child {
                page-break-before: always
            }
        }
    </style>
</head>
<body>
<div id='invoice'>
    <div class='invoice overflow-auto'>
        <div style='min-width: 600px'>
            <header>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="<?= site() ?>">
                            <img src="<?= assets('img/SGFA.png')?>" alt="logo" width="auto"
                                 height="75"/>
                        </a>
                    </div>
                    <div class='col company-details'>
                        <div>Av de Moçambique nr 11, Maputo, Moçambique</div>
                        <div>Tel: +258 846099218</div>
                        <div>Email: aguasaz68@gmail.com</div>
                        <div>Website: www.aguasaz.online</div>
                    </div>
                </div>
            </header>
            <main>
    
                <br><br>
                <h4 class="text-center">RECIBO <?= $recibo->ID ?></h4>
                <div class="row contacts" style="padding-bottom:10px;margin-bottom:10px;">
                    <div class="col invoice-to">
                        <div class="text-gray-light">A favor de:</div>
                        <div class="to">Nome : <?= $cliente->Nome ?></div>
                        <div class="address">Morada : <?= $cliente->Morada ?></div>
                        <div class="email">Celular : <?= $cliente->Celular ?></div>
                    </div>
                    <div class="col invoice-details">
                        <div class="date">Data : <?= $recibo->Date ?></div>
                    </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td class="no" style="background:deeppink;">01</td>
                            <td class="text-left">
                                <h3 class="text-dark">Água Potável</h3>
                            </td>
                            <td class="total" style="background:deeppink;"><?= $recibo->Valor ?> MT</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">TOTAL</td>
                            <td><?= $recibo->Valor ?> MT</td>
                        </tr>
                    </tfoot>
                </table>
            </main>
                <div class="alert alert-success">PAGO via <?= $recibo->Meio ?></div>
            <footer>
                Documento processado por computador,é valido mesmo sem carimbo ou assinatura
            </footer>
        </div>
    </div>
</div>
</body>
</html>