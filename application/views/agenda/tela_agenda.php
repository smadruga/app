<?php if (isset($msg)) echo $msg; ?>

<!--<div id="dp" class="col-md-2"></div>
<div id="datepickerinline" class="col-md-2"></div>
<div id="calendar" class="col-md-8"></div>-->

<div id="datepickerinline" class="col-md-2"></div>
<div id="calendar" class="col-md-10"></div>

<div id="fluxo" class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="fluxo" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-sm vertical-align-center">
            <div class="modal-content">

                <div class="modal-body text-center">
                    <button type="button" id="AgendarEvento" onclick="redirecionar(1)" class="btn btn-info">Agendar Evento</button>
                    <button type="button" id="MarcarConsulta" onclick="redirecionar(2)" class="btn btn-primary">Marcar Consulta</button>
                    
                    <input type="hidden" id="start" />
                    <input type="hidden" id="end" />
                </div>

                </div>
            </div>
        </div>
    </div>