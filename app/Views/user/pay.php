<?php $this->layout('user::_template_') ?>

<h1 class="h3 mb-2 font-weight-bold text-gray-800"><i class="fa fa-money-bill-wave"></i> Pagamento Online</h1>
<h5 class="text-dark">Este é um <b>ambiente seguro</b> para efectuar o pagamento das suas facturas, ao clicar em pagar,
    verá no celular uma notificação push que lhe pede para <b>confirmar o pagamento digitando o seu PIN do M-pesa</b>, após
    verificar que todos os dados estão correctos confirme a transação e espere que o processamento aqui nesta página
    termine para confirmar que a transação foi efectuada com sucesso.
</h5>

<div class="row align-items-center justify-content-center">
    <div class="col-xl-6 col-lg-6 col-md-6">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="col p-4">
                    <div class="text-center">
                        <img src="<?= assets("img/Mpesa.png") ?>" class="img-fluid" height="30px"
                             width="200px" alt="logo">
                        <p class="text-left font-weight-bold text-dark">
                            Verifique sempre os dados antes de clicar no botão de pagar, e não
                            recarregue a página ou saia durante o processo de pagamento
                        </p>
                        <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                            <button type="button" class="close" onclick="hide()">
                                <span><i class="far fa-times-circle"></i></span>
                            </button>
                        </div>
                    </div>
                    <form onsubmit="pay_invoice(event)">
                        <div class="form-group font-weight-bold">
                            <label for="invoice_id"><i class="fa fa-file-invoice-dollar"></i> Factura</label>
                            <select class="form-control" name="invoice_id" id="invoice_id">
                                <option selected disabled>Número - Data - Valor em dívida</option>
                                <?php
                                    foreach ($invoices as $invoice) :
                                        echo '<option value="'.$invoice->id.'">Nr '.$invoice->id.' - '.$invoice->date_added.
                                            ' - '.$invoice->debt.' MZN</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group font-weight-bold">
                            <label><i class="fa fa-phone-square-alt"></i> Número de Celular</label>
                            <input type="tel" class="form-control" name="phone"
                                   placeholder="Digite aqui o número do Mpesa" maxlength="9"
                                   pattern="[8]{1}[4]{1}[0-9]{7}" required>
                        </div>
                        <div class="form-group font-weight-bold">
                            <label><i class="fa fa-dollar-sign"></i> Valor a pagar (min 100.00 MZN)</label>
                            <input type="number" min="100.00" class="form-control" name="amount"
                                   placeholder="Digite aqui o valor que pretende pagar" required>
                        </div>
                        <div class="form-group text-center pt-3">
                            <button type="submit" class="btn btn-block btn-success">
                                <b>PAGAR A FACTURA</b> <i class="fa fa-check-circle"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->start('scripts') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"
            integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?= assets('js/functions.js') ?>" type="text/javascript"></script>
    <script src="<?= assets('js/pay.js') ?>" type="text/javascript"></script>
<?php $this->end() ?>