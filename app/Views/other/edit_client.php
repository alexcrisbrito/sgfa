<?php $this->layout($template); ?>


<h4>Atualização de Dados</h4>
<div class="row">

    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header pb-1">
                <h5 class="card-title text-primary p-0">Foto</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img width="100px" height="100px" class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                    <div class="form-group pt-5">
                        <button class="btn btn-success" type="submit">Enviar imagem <i class="fa fa-upload"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header pb-1">
                <h5 class="card-title text-primary p-0">Dados do Cliente</h5>
            </div>
            <div class="card-body">
                <div class="col-sm-8 alert {type} alert-dismissible fade show" hidden id="callback">
                    <button type="button" class="close" onclick="hide()">
                        <span>&times;</span>
                    </button>
                </div>
                <form class="user" id="form">
                    <div class="form-group">
                        <label for="Nome" class="pl-2">Nome Completo</label>
                        <input value="<?= $client->Nome ?>" type="text" name="Nome"
                               class="form-control form-control-user" maxlength="255" id="Nome"
                               placeholder="Digite aqui o nome do cliente">
                    </div>
                    <div class="form-group">
                        <label for="Celular" class="pl-2">Número de Celular</label>
                        <input value="<?= $client->Celular ?>" type="text" name="Celular"
                               class="form-control form-control-user" maxlength="9" id="Celular"
                               placeholder="Digite aqui o número de celular">
                    </div>
                    <div class="form-group">
                        <label for="Morada" class="pl-2">Morada</label>
                        <input value="<?= $client->Morada ?>" type="text" name="Morada"
                               class="form-control form-control-user" maxlength="150" id="Morada"
                               placeholder="Digite aqui a morada">
                    </div>
                    <input type="hidden" value="<?= $client->ID ?>" name="Id">
                    <div class="form-group col-sm-5">
                        <div class="row-cols-2">
                            <button type="submit" class="btn btn-success">Atualizar <i class="fas fa-check-circle"></i>
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
    let form = document.getElementById('form');
    let page = "<?= $router->route("admin.clientes.atualizar")?>";
</script>