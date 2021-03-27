<?php $this->layout("worker::_template_"); ?>

<h1 class="h3 mb-2 text-gray-800">Mensagens</h1>

<h5 class="text-dark pt-2 pb-2">Créditos API: <?= number_format($credits,0) ?> SMS</h5>
<p>Cada SMS custa 1 crédito, dependendo do seu tamanho e conteúdo (se incluir caracteres especiais, mais de 170 caracteres ou letras com acentos) pode gastar mais de 1 crédito</p>

<div class="row">

    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header pb-1">
                <h5 class="card-title text-primary p-0">Envio de SMS</h5>
            </div>
            <div class="card-body">
                <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
                    <button type="button" class="close" onclick="hide()">
                        <span>&times;</span>
                    </button>
                </div>
                <form class="user" id="form">
                    <div class="form-group">
                        <label for="Cliente" class="pl-2">Cliente / Número de Celular</label>
                        <select class="js-example-basic-single js-states form-control" name="cliente" id="Cliente">
                            <option></option>
                            <option value="0">Todos Clientes</option>
                            <?php foreach ($clients as $client) {
                                echo "<option value='{$client->Celular}'>{$client->Nome}</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Corpo" class="pl-2">Corpo da Mensagem</label>
                        <textarea name="corpo" rows="8" class="form-control" id="Corpo"
                                  placeholder="Digite aqui a mensagem a enviar" maxlength="175"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="row-cols-2">
                            <button type="submit" id="submit" class="btn btn-success">Emitir <i
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

</div>

<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            placeholder: 'Selecione o cliente',
            tags:true
        });
    });

    let form = document.getElementById('form');
    let page = '<?= $router->route("admin.messages.send") ?>';
</script>