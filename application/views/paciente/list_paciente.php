<br>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Paciente</th>
            <th>Nascimento</th>
            <th>Telefone</th>
        </tr>
    </thead>
    <tbody>
        <?php

        foreach ($query->result_array() as $row)
        {
            
            if ($_SESSION['agenda'])
                $url = base_url() . 'consulta/cadastrar/' . $row['idApp_Paciente'];
            else
                $url = base_url() . 'paciente/prontuario/' . $row['idApp_Paciente'];
                    
            echo '<tr class="clickable-row" data-href="' . $url . '">';
                echo '<td>' . $row['NomePaciente'] . '</td>';
                echo '<td>' . $row['DataNascimento'] . '</td>';
                echo '<td>' . $row['Telefone'] . '</td>';
            echo '</tr>';            
        }
        ?>

    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total encontrado: <?php echo $query->num_rows(); ?> resultado(s)</th>
        </tr>
    </tfoot>
</table>



