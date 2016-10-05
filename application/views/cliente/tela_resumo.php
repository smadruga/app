<div class="panel-body">
    <div class="row">
        <div class="col-md-3 col-lg-3 " align="center"> 
            <img alt="User Pic" width="150px" src="http://www.accrinet.com/images/3030_orig.png" class="img-circle img-responsive">    
            <br>
            <a href="#" type="button" class="btn btn-info"><span class="glyphicon glyphicon-camera"></span> Alterar Foto</a>
        </div>

        <div class=" col-md-9 col-lg-9 "> 
            <table class="table table-user-information">
                <tbody>

                    <tr>
                        <td class="col-md-4 col-lg-4"><span class="glyphicon glyphicon-user"></span> Identificador:</td>
                        <td><?php echo $query['idApp_Paciente']; ?></td>
                    </tr>
                    
                    <tr>
                        <td class="col-md-3 col-lg-3"><span class="glyphicon glyphicon-user"></span> Nome do Cliente:</td>
                        <td><?php echo $query['NomePaciente']; ?></td>
                    </tr>
                    <tr>
                        <td><span class="glyphicon glyphicon-gift"></span> Data de Nascimento:</td>
                        <td><?php echo $query['DataNascimento']; ?></td>
                    </tr>
                    <tr>
                        <td><span class="glyphicon glyphicon-phone-alt"></span> Telefone:</td>
                        <td><?php echo $query['Telefone']; ?></td>
                    </tr>
                    <tr>
                        <td><span class="glyphicon glyphicon-heart"></span> Sexo:</td>
                        <td><?php echo $query['Sexo']; ?></td>
                    </tr>
                    <tr>
                        <td><span class="glyphicon glyphicon-home"></span> Endereço:</td>
                        <td><?php echo $query['Endereco'] . ' ' . $query['Bairro'] . ' ' . $query['Municipio']; ?></td>
                    </tr>
                    <tr>
                        <td><span class="glyphicon glyphicon-envelope"></span> E-mail:</td>
                        <td><?php echo $query['Email']; ?></td>
                    </tr>
                    <tr>
                        <td><span class="glyphicon glyphicon-file"></span> Obs:</td>
                        <td><?php echo nl2br($query['Obs']); ?></td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>
</div>