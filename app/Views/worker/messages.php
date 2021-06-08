<?php $this->layout("worker::_template_") ?>

<h1 class="h3 mb-2 font-weight-bold text-gray-800">
    <i class="fa fa-comment-alt"></i> Mensagens
</h1>

<h5 class="text-dark pt-2 font-weight-bold pb-2">Créditos: <?= number_format($credits,0) ?> SMS</h5>
<p class="font-weight-bold">Cada SMS custa 1 crédito, dependendo do seu tamanho e conteúdo (se incluir caractêres especiais, letras com acentos) pode gastar mais de 1 crédito</p>

<div class="row">

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header pb-1">
                <h5 class="card-title text-primary font-weight-bold p-0">Envio de SMS</h5>
            </div>
            <div class="card-body">
                <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
                    <button type="button" class="close" onclick="hide()">
                        <span>&times;</span>
                    </button>
                </div>
                <form class="user" onsubmit="post_message(event)">
                    <div class="form-group">
                        <label for="client" class="pl-2">Cliente / Número de Celular</label>
                        <select class="js-example-basic-single js-states form-control" name="client" id="client" required>
                            <option></option>
                            <option value="0">Todos Clientes</option>
                            <?php foreach ($clients as $client) {
                                echo "<option value='$client->phone'>$client->name $client->surname</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="body" class="pl-2">Corpo da Mensagem</label>
                        <textarea name="body" rows="4" class="form-control" id="body"
                                  placeholder="Digite aqui a mensagem a enviar" maxlength="175" required></textarea>
                        <div id="sms-counter">
                            <p><span class="remaining"></span>/<span class="per_message"></span> <br>
                                Créditos em uso: <span class="messages"></span></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row-cols-2">
                            <button type="submit" id="submit" class="btn btn-success">Enviar <i
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

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header pb-1">
                <h5 class="card-title text-primary font-weight-bold p-0">Solicitar créditos</h5>
            </div>
            <div class="card-body">
                <form class="user" onsubmit="post_request(event)">
                    <div class="form-group">
                        <label for="amount" class="pl-2">Número de SMS's</label>
                        <input type="number" id="amount" class="form-control" name="amount_sms"
                               value="100" min="100" max="20000">
                    </div>

                    <p class="font-weight-bold">Estimativa: <span class="font-weight-normal" id="amount"></span></p>

                    <p><span class="font-weight-bold">NOTAS:</span> <br>
                        &bullet; Nunca deposite o valor aqui estimado por adiantado, espere pela resposta e depois efetue o pagamento <br>
                        &bullet; As solicitações são apenas recebidas de 2ª à 6ª feira com mínimo de 100 SMS's<br>
                        &bullet; O preço aqui simulado pode ser diferente do preço atual</p>
                    <div class="form-group">
                        <div class="row-cols-2">
                            <button type="submit" id="submit" class="btn btn-success">
                                Solicitar <i class="fas fa-check-circle"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php $this->start('scripts'); ?>
<script src="<?= assets("js/sms_counter.min.js") ?>" type="text/javascript"></script>
<script src="<?= assets("js/messages.js") ?>" type="text/javascript"></script>
<?php $this->end(); ?>
