<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Águas AZ - Factura <?= $dados->ID ?></title>
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
            margin-top: -10px;
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
            padding: 10px;
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
            padding: 10px 10px;
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
                <div class='row contacts'>
                    <div class='col invoice-to'>
                        <div class='text-gray-light'>Factura para:</div>
                        <div class='to'>Nome: <?= $cliente->Nome ?></div>
                        <div class='address'>Morada: <?= $cliente->Morada ?></div>
                        <div class='email'>Celular: <?= $cliente->Celular ?></div>
                    </div>
                    <div class='col invoice-details'>
                        <h4 class='invoice-id text-success'>FACTURA <?= $dados->ID ?></h4>
                        <div class='date'>Data: <?= $dados->Date ?></div>
                        <div class='due-date'>Expiração: <?php
                            $date = date_create_from_format("d/m/Y",$dados->Date);
                            date_add($date,date_interval_create_from_date_string("1 month"));
                            echo date_format($date,"10/m/Y");
                            ?>
                        </div>
                        <div class='due-date'>Estado: <?php
                            switch($dados->Estado){
                                case 1:
                                    echo "Em dívida";
                                    break;

                                case 2:
                                    echo "Paga";
                                    break;

                                case 3:
                                    echo "Cancelada";
                                    break;

                                case 4:
                                    echo "Atrasada";
                                    break;
                            } ?></div>
                    </div>
                </div>
                <table border='0' cellspacing='0' cellpadding='0' class="mt-1">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th class='text-left'>DESCRIÇÃO</th>
                        <th class='text-right'>VALOR</th>
                        <th class='text-right'>QUANTIDADE</th>
                        <th class='text-right'>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class='no bg-success'>01</td>
                        <td class='text-left'>
                            <h3>Água Potável</h3>
                        </td>
                        <td class='unit'><?= BUSINESS_MODEL["Price"] ?></td>
                        <td class='qty'><?= $dados->Consumo ?> m3</td>
                        <td class='total bg-success'><?= number_format($dados->Valor,2,",",".") ?> MT</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan='2'></td>
                        <td colspan='2' class='text-danger'>MULTA</td>
                        <td class='text-danger'> <?= number_format($dados->Multa,2,",",".") ?> MT</td>
                    </tr>
                    <tr>
                        <td colspan='2'></td>
                        <td colspan='2'>TOTAL</td>
                        <td> <?= number_format($dados->Valor + $dados->Multa,2,",",".") ?> MT</td>
                    </tr>
                    </tfoot>
                </table>
            </main>
            <div class='row contacts'>
                <div class='col invoice-to'>
                    <div class='alert alert-danger'>
                        N.B: A taxa de 0 à 6 m3 é 420,00 MT. De 6.1m3 o consumo é calculado com taxa de 70,00 MT/m3. 
                        O pagamento deve ser do dia 25 à 10 do mês seguinte, após o prazo a multa é de 200,00 MT.
                    </div>
                    <h5 class='text-gray-light'>Detalhes para pagamento:</h5>
                    <p>
                        Milleninum Bim: 51504576<br>BCI: 777678210003<br>M-Pesa & Conta Móvel: 846099218
                        </p>
                </div>
            </div>
        </div>
    </div>
</div>