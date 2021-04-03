<?php $this->layout("_template")  ?>
<div class="row align-items-center justify-content-center">

    <div class="col-xl-6 col-lg-6 col-md-6">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-3">
                            <div class="text-left">
                                <img src="<?= assets("img/SGFA.png") ?>" class="text-center mb-4" height="70px"
                                     width="200px" alt="SGFA_logo">
                                <h1 class="h4 text-gray-900 mb-4">
                                    <button class="btn btn-primary btn-circle">3</button>
                                    Dados do negócio
                                </h1>
                                <div class="alert {type} alert-dismissible fade show" hidden id="callback">
                                    <button type="button" class="close" onclick="hide()">
                                        <span>&times;</span>
                                    </button>
                                </div>
                            </div>
                            <form class="user" action="" method="post">
                                <div class="form-group">
                                    <label for="price"><i class="fa fa-dollar-sign"></i> Preço por m<sup>3</sup></label>
                                    <input type="text" class="form-control" id="price"
                                           name="price" required>
                                </div>
                                <div class="form-group">
                                    <label for="expiry_mode"><i class="fa fa-question-circle"></i> Modo de expiração de factura</label>
                                    <select class="form-control" name="expiry_mode" id="expiry_mode" required>
                                        <option value="" selected disabled>Selecione o modo</option>
                                        <option value="1">X dias após a emissão</option>
                                        <option value="2">Até dia X do mês seguinte</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="date_expiry"><i class="fa fa-calendar"></i> Dia da expiração</label>
                                    <input type="number" max="30" min="1" class="form-control" id="date_expiry"
                                           name="expiry_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="fine"><i class="fa fa-dollar-sign"></i> Valor de Multa</label>
                                    <input type="number" value="0.00" class="form-control" id="fine"
                                           name="fine" required>
                                </div>
                                <div class="form-group">
                                    <label for="wallet"><i class="fa fa-wallet"></i> Detalhes de pagamento</label>
                                    <textarea class="form-control" name="payment_method" id="wallet"
                                              placeholder="Digite 1 detalhe por linha, ex:&#10;Mpesa - 841234567&#10;MBim - 8212191"
                                              cols="30" rows="5" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success">
                                        Terminar <i class="fa fa-arrow-circle-right"></i>
                                    </button>
                                </div>
                            </form>
                            <br>
                            <div class="text-center">
                                <a class="small" href="https://nextgenit-mz.com">NextGen IT &copy; SGFA
                                    v<?= SITE["version"] ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>