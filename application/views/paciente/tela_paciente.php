<?php if (isset($msg)) echo $msg; ?>

<div class="row">

    <div class="col-md-2"></div>
    <div class="col-md-8">

        <?php echo validation_errors(); ?>
        
        <div class="panel panel-<?php echo $panel; ?>">
            <div class="panel-heading"><strong><?php echo $titulo; ?></strong></div>
            
            <?php if ( !preg_match("/evento\b/", uri_string()) && (
                    (substr(uri_string(), 0, strpos(uri_string(), '/')) != 'paciente' && ($metodo == 1 || $metodo == 3) ) || 
                    ($metodo != 1 && $metodo != 3) 
                    ) )  { ?>
            
            <div class="panel panel-footer text-center">
                    <!--<a type="button" onClick="history.go(-1); return true;" class="btn btn-sm btn-warning">
                        <span class="glyphicon glyphicon-arrow-left"></span> Voltar</a>-->
                    <a href="<?php echo base_url() . 'paciente/prontuario/' . $resumo['idApp_Paciente']; ?>" type="button" class="btn btn-sm btn-warning">
                        <span class="glyphicon glyphicon-list-alt"></span> Prontuário</a>
                    <a href="<?php echo base_url() . 'consulta/cadastrar/' . $resumo['idApp_Paciente']; ?>" type="button" class="btn btn-sm btn-info">
                        <span class="glyphicon glyphicon-check"></span> Marcar Consulta</a>
                    <a href="<?php echo base_url() . 'consulta/listar/' . $resumo['idApp_Paciente']; ?>" type="button" class="btn btn-sm btn-info">
                        <span class="glyphicon glyphicon-list"></span> Listar Consultas</a>
                    <a href="<?php echo base_url() . 'paciente/alterar/' . $resumo['idApp_Paciente']; ?>" type="button" class="btn btn-sm btn-info">
                        <span class="glyphicon glyphicon-edit"></span> Editar Dados</a>
                    <a href="<?php echo base_url() . 'paciente/excluir/' . $resumo['idApp_Paciente']; ?>" type="button" class="btn btn-sm btn-danger">
                        <span class="glyphicon glyphicon-trash"></span> Excluir Paciente</a>
            </div>
            
            <?php } ?>
            
            <?php if (isset($tela)) echo $tela; ?>

        </div>

    </div>
    <div class="col-md-2"></div>

</div>