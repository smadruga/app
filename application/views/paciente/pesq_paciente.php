<?php if (isset($msg)) echo $msg; ?>

<div class="row">

    <div class="col-md-2"></div>
    <div class="col-md-8">

        <?php echo validation_errors(); ?>

        <div class="panel panel-primary">

            <div class="panel-heading"><strong><?php echo $titulo; ?></strong></div>
            <div class="panel-body">

                <p>Informe o <b>Nome do Paciente, Data de Nascimento ou Telefone</b>:</p>

                <div class="row">
                    <?php echo form_open('paciente/pesquisar', 'role="form"'); ?>
                    <div class="col-md-6">

                        <input type="text" id="inputText" class="form-control"
                               autofocus name="Pesquisa" value="<?php echo set_value('Pesquisa', $Pesquisa); ?>">

                    </div>

                    <div class="col-md-6">
                        <button class="btn btn-sm btn-primary" name="pesquisar" value="0" type="submit">
                            <span class="glyphicon glyphicon-search"></span> Pesquisar
                        </button>
                    </div>
                    </form>
                </div>

                <?php if (isset($list)) echo $list; ?>
                
            </div>

        </div>

    </div>
    <div class="col-md-2"></div>

</div>


