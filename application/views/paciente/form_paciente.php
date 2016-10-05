<?php if (isset($msg)) echo $msg; ?>

<div class="row">

    <div class="col-md-12">

        <?php echo validation_errors(); ?>

        <div class="panel-body">

            <?php echo form_open_multipart($form_open_path); ?>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="NomePaciente">Nome do Paciente: *</label>
                        <input type="text" class="form-control" id="NomePaciente" maxlength="255" <?php echo $readonly; ?>
                               name="NomePaciente" autofocus value="<?php echo $query['NomePaciente']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="DataNascimento">Data de Nascimento:</label>
                        <input type="text" class="form-control Date" maxlength="10" <?php echo $readonly; ?>
                               name="DataNascimento" placeholder="DD/MM/AAAA" value="<?php echo $query['DataNascimento']; ?>">
                    </div>
                </div>
            </div> 

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="Telefone">Telefone:</label>
                        <input type="text" class="form-control" id="Telefone" maxlength="100" <?php echo $readonly; ?>
                               name="Telefone" value="<?php echo $query['Telefone']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="Sexo">Sexo:</label>
                        <select data-placeholder="Selecione um TROCA..." class="form-control" <?php echo $readonly; ?>
                                id="Sexo" name="Sexo">
                            <option value=""></option>
                            <?php
                            foreach ($select['Sexo'] as $key => $row) {
                                if ($query['Sexo'] == $key) {
                                    echo '<option value="' . $key . '" selected="selected">' . $row . '</option>';
                                } else {
                                    echo '<option value="' . $key . '">' . $row . '</option>';
                                }
                            }
                            ?>   
                        </select>
                    </div>
                </div>
            </div> 

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="Endereco">Endreço:</label>
                        <input type="text" class="form-control" id="Endereco" maxlength="100" <?php echo $readonly; ?>
                               name="Endereco" value="<?php echo $query['Endereco']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="Bairro">Bairro:</label>
                        <input type="text" class="form-control" id="Bairro" maxlength="100" <?php echo $readonly; ?>
                               name="Bairro" value="<?php echo $query['Bairro']; ?>">
                    </div>
                </div>
            </div> 

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="Municipio">Município:</label><br>
                        <select data-placeholder="Selecione um Município..." class="form-control" <?php echo $disabled; ?>
                                id="Municipio" name="Municipio">
                            <option value=""></option>
                            <?php
                            foreach ($select['Municipio'] as $key => $row) {
                                if ($query['Municipio'] == $key) {
                                    echo '<option value="' . $key . '" selected="selected">' . $row . '</option>';
                                } else {
                                    echo '<option value="' . $key . '">' . $row . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="Email">E-mail:</label>
                        <input type="text" class="form-control" id="Bairro" maxlength="100" <?php echo $readonly; ?>
                               name="Email" value="<?php echo $query['Email']; ?>">
                    </div>                        
                </div>
            </div> 

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="Obs">OBS:</label>
                        <textarea class="form-control" id="Obs" <?php echo $readonly; ?>
                                  name="Obs"><?php echo $query['Obs']; ?></textarea>
                    </div>
                </div>
            </div>                 

            <br>

            <div class="form-group">
                <div class="row">
                    <input type="hidden" name="idApp_Paciente" value="<?php echo $query['idApp_Paciente']; ?>">
                    <?php if ($metodo == 3) { ?>
                        <div class="col-md-12 text-center">                            
                            <button class="btn btn-lg btn-danger" id="inputDb" data-loading-text="Aguarde..." name="submit" value="1" type="submit">
                                <span class="glyphicon glyphicon-trash"></span> Excluir
                            </button>
                            <button class="btn btn-lg btn-warning" id="inputDb" onClick="history.go(-1);
                                    return true;">
                                <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
                            </button>
                        </div>                        
                    <?php } else { ?>
                        <div class="col-md-6">                            
                            <button class="btn btn-lg btn-primary" id="inputDb" data-loading-text="Aguarde..." type="submit">
                                <span class="glyphicon glyphicon-save"></span> Salvar
                            </button>                            
                        </div>
                    <?php } ?>
                </div>
            </div>                

            </form>

        </div>

    </div>

</div>