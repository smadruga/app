<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#proxima" aria-controls="proxima" role="tab" data-toggle="tab">Próximas Consultas</a></li>
        <li role="presentation"><a href="#anterior" aria-controls="anterior" role="tab" data-toggle="tab">Histórico de Consultas</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Próximas Consultas -->
        <div role="tabpanel" class="tab-pane active" id="proxima">
            
            <?php
            if ($proxima) {
            
                foreach ($proxima->result_array() as $row)
                {

                    $row['DataInicio'] = explode(' ', $row['DataInicio']);
                    $row['DataFim'] = explode(' ', $row['DataFim']);

                    echo '<div data-href="' . base_url() . 'consulta/alterar/' . $row['idApp_Paciente'] . '/' . $row['idApp_Consulta'] . '" '
                            . 'class="clickable-row bs-callout bs-callout-' . $this->basico->tipo_status_cor($row['idTab_Status']) . '">';
                        echo '<h4><b>Status: ' . $row['Status'] . '</b></h4>';
                        echo '<p><b>Tipo de Consulta:</b> ' . $row['TipoConsulta'] . '</p>';
                        echo '<p><b>Data:</b> ' . $this->basico->mascara_data($row['DataInicio'][0], 'barras') . ' '
                                . 'das ' . substr($row['DataInicio'][1], 0, 5) . ' às ' . substr($row['DataFim'][1], 0, 5) . '</p>';
                        echo '<p><b>Procedimento:</b><br> ' . nl2br($row['Procedimento']) . '</p>';
                        echo '<p><b>Obs:</b><br> ' . nl2br($row['Obs']) . '</p>';
                    echo '</div>';            
                }

            }
            else {
                echo '<br><div class="alert alert-info text-center" role="alert"><b>Nenhuma consulta registrada</b></div>';
            }
            ?>

        </div>

        <!-- Histórico de Consultas -->
        <div role="tabpanel" class="tab-pane" id="anterior">

            <?php
            if ($anterior) {
                
                foreach ($anterior->result_array() as $row)
                {

                    $row['DataInicio'] = explode(' ', $row['DataInicio']);
                    $row['DataFim'] = explode(' ', $row['DataFim']);

                    echo '<div data-href="' . base_url() . 'consulta/alterar/' . $row['idApp_Paciente'] . '/' . $row['idApp_Consulta'] . '" '
                            . 'class="clickable-row bs-callout bs-callout-' . $this->basico->tipo_status_cor($row['idTab_Status']) . '">';
                        echo '<h4><b>Status: ' . $row['Status'] . '</b></h4>';
                        echo '<p><b>Tipo de Consulta:</b> ' . $row['TipoConsulta'] . '</p>';
                        echo '<p><b>Data:</b> ' . $this->basico->mascara_data($row['DataInicio'][0], 'barras') . ' '
                                . 'das ' . substr($row['DataInicio'][1], 0, 5) . ' às ' . substr($row['DataFim'][1], 0, 5) . '</p>';
                        echo '<p><b>Procedimento:</b><br> ' . nl2br($row['Procedimento']) . '</p>';
                        echo '<p><b>Obs:</b><br> ' . nl2br($row['Obs']) . '</p>';
                    echo '</div>';      
                }
            
            }
            else {
                echo '<br><div class="alert alert-info text-center" role="alert"><b>Nenhuma consulta registrada</b></div>';
            }            
            ?>            
            
        </div>
    </div>

</div>