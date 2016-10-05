<?php if (isset($msg)) echo $msg; ?>

<div class="row">

    <div class="col-md-12">

        <?php echo validation_errors(); ?>

        <div class="panel-body">

            <?php echo form_open_multipart($form_open_path); ?>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        <label for="Data">Data:</label>
                        <div class="input-group <?php echo $date; ?>">
                            <input type="text" class="form-control" <?php echo $readonly; ?>
                                   name="Data" value="<?php echo $query['Data']; ?>">
                            <span class="input-group-addon" disabled>
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-7 form-inline">
                        <div class="form-group">
                            <label for="Hora">Hora:</label><br>
                            De 
                            <div class="input-group <?php echo $clockpicker; ?>">
                                <input type="text" class="form-control" <?php echo $readonly; ?>
                                       accept=""name="HoraInicio" value="<?php echo $query['HoraInicio']; ?>">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>

                            Até
                            <div class="input-group <?php echo $clockpicker; ?>">
                                <input type="text" class="form-control" <?php echo $readonly; ?>
                                       accept=""name="HoraFim" value="<?php echo $query['HoraFim']; ?>">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="idTab_TipoConsulta">Tipo de Consulta:</label><br>
                        <div class="radio">
                            <?php
                            foreach ($select['TipoConsulta'] as $key => $row) {
                                if (!$query['idTab_TipoConsulta'])
                                    $query['idTab_TipoConsulta'] = 1;

                                if ($query['idTab_TipoConsulta'] == $key) {
                                    echo ''
                                    . '<label>
                                                <input type="radio" name="idTab_TipoConsulta" checked="checked" value="' . $key . '"> ' . $row . '
                                            </label> ';
                                } else {
                                    echo ''
                                    . '<label>
                                                <input type="radio" name="idTab_TipoConsulta" value="' . $key . '"> ' . $row . '
                                            </label> ';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div> 

            <?php if ($metodo == 2) { ?>
            
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="idTab_Status">Status:</label><br>
                        <div class="radio">
                            <h4>
                            <?php
                            foreach ($select['Status'] as $key => $row) {
                                if (!$query['idTab_Status'])
                                    $query['idTab_Status'] = 1;

                                if ($query['idTab_Status'] == $key) {
                                    echo ''
                                        . '<div class="col-md-2"><label>
                                            <input type="radio" name="idTab_Status" checked="checked" value="' . $key . '"> '
                                            . '<span class="label label-' . $this->basico->tipo_status_cor($key) . '">' . $row . '</span>'
                                        . '</label></div>';
                                } else {
                                    echo ''
                                        . '<div class="col-md-2"><label>
                                            <input type="radio" name="idTab_Status" value="' . $key . '"> '
                                            . '<span class="label label-' . $this->basico->tipo_status_cor($key) . '">' . $row . '</span>'
                                        . '</label></div>';
                                }
                            }
                            ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div> 
            
            <?php } ?>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="Procedimento">Procedimento:</label>
                        <textarea class="form-control" id="Procedimento"
                                  name="Procedimento"><?php echo $query['Procedimento']; ?></textarea>
                    </div>
                </div>
            </div>                 

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="Obs">Obs:</label>
                        <textarea class="form-control" id="Obs"
                                  name="Obs"><?php echo $query['Obs']; ?></textarea>
                    </div>
                </div>
            </div>   

            <br>

            <div class="form-group">
                <div class="row">
                    <input type="hidden" name="idApp_Consulta" value="<?php echo $query['idApp_Consulta']; ?>">
                    <input type="hidden" name="idApp_Agenda" value="<?php echo $_SESSION['log']['Agenda']; ?>">
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