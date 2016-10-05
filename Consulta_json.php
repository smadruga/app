<?php

$link = mysql_connect('159.203.125.243', 'usuario', '20UtpJ15');
if (!$link) {
    die('Não foi possível conectar: ' . mysql_error());
}

$db = mysql_select_db('app', $link);
if (!$db) {
    die('Não foi possível selecionar banco de dados: ' . mysql_error());
}

#echo 'Conexão bem sucedida';

session_start();

/*

  'SELECT
  C.idApp_Consulta,
  C.idApp_Paciente,
  P.NomePaciente,
  C.DataInicio,
  C.DataFim,
  C.Procedimento,
  C.Obs,
  TC.TipoConsulta,
  C.idTab_Status
  FROM
  app.App_Agenda AS A,
  app.App_Consulta AS C,
  app.App_Paciente AS P,
  app.Tab_TipoConsulta AS TC
  WHERE
  A.idApp_Agenda = ' . $_SESSION['log']['Agenda'] . ' AND
  A.idApp_Agenda = C.idApp_Agenda AND
  C.idTab_TipoConsulta = TC.idTab_TipoConsulta AND
  C.idApp_Paciente = P.idApp_Paciente
  ORDER BY C.DataInicio ASC'

  SELECT
  C.idApp_Consulta,
  C.idApp_Paciente,
  P.NomePaciente,
  C.DataInicio,
  C.DataFim,
  C.Procedimento,
  C.Obs,
  TC.idTab_TipoConsulta,
  C.idTab_Status
  FROM
  app.App_Agenda AS A,
  app.App_Consulta AS C
  LEFT JOIN app.App_Paciente AS P ON C.idApp_Paciente = P.idApp_Paciente
  LEFT JOIN Tab_TipoConsulta AS TC ON C.idTab_TipoConsulta = TC.idTab_TipoConsulta
  #app.App_Paciente AS P RIGHT JOIN app.App_Consulta AS C ON P.idApp_Paciente = C.idApp_Paciente,
  #app.Tab_TipoConsulta AS TC RIGHT JOIN app.App_Consulta AS C2 ON TC.idTab_TipoConsulta = C2.idTab_TipoConsulta
  WHERE
  A.idApp_Agenda = 1 AND
  A.idApp_Agenda = C.idApp_Agenda
  #C.idTab_TipoConsulta = TC.idTab_TipoConsulta AND
  #C.idApp_Paciente = P.idApp_Paciente
  ORDER BY C.DataInicio ASC


 */

$result = mysql_query(
        'SELECT
            C.idApp_Consulta,
            C.idApp_Paciente,
            P.NomePaciente,
            C.DataInicio,
            C.DataFim,
            C.Procedimento,
            C.Obs,
            TC.TipoConsulta,
            C.idTab_Status,
            C.Evento
        FROM 
            app.App_Agenda AS A,
            app.App_Consulta AS C 
                    LEFT JOIN app.App_Paciente AS P ON C.idApp_Paciente = P.idApp_Paciente
                    LEFT JOIN Tab_TipoConsulta AS TC ON C.idTab_TipoConsulta = TC.idTab_TipoConsulta
        WHERE
            A.idApp_Agenda = ' . $_SESSION['log']['Agenda'] . ' AND
            A.idApp_Agenda = C.idApp_Agenda
        ORDER BY C.DataInicio ASC'
);

while ($row = mysql_fetch_assoc($result)) {

    if ($row['Evento']) {
        $c = '_evento';
        //(strlen(utf8_encode($row['Obs'])) > 20) ? $title = substr(utf8_encode($row['Obs']), 0, 20).'...' : $title = utf8_encode($row['Obs']);
        $title = utf8_encode($row['Obs']);
    } else {
        $c = '/' . $row['idApp_Paciente'];
        $title = utf8_encode($row['NomePaciente']);
    }

    $url = 'consulta/alterar' . $c . '/' . $row['idApp_Consulta'];

    if ($row['DataFim'] < date('Y-m-d H:i:s')) {

        //$url = false;
        $textColor = 'grey';

        if ($row['Evento'])
            $status = '#e6e6e6';
        else {
            if ($row['idTab_Status'] == 1)
                $status = '#EBCCA1';
            elseif ($row['idTab_Status'] == 2)
                $status = ' #95d095';
            elseif ($row['idTab_Status'] == 3)
                $status = '#99B6D0';
            else
                $status = '#E4BEBD';
        }
    }
    else {

        //$url = 'consulta/alterar/'.$row['idApp_Paciente'].'/'.$row['idApp_Consulta'];
        $textColor = 'black';

        if ($row['Evento'])
            $status = '#a6a6a6';
        else {
            if ($row['idTab_Status'] == 1)
                $status = '#f0ad4e';
            elseif ($row['idTab_Status'] == 2)
                $status = '#5cb85c';
            elseif ($row['idTab_Status'] == 3)
                $status = 'darken(#428bca, 6.5%)';
            else
                $status = '#d9534f';
        }
    }

    $event_array[] = array(
        'id' => $row['idApp_Consulta'],
        'title' => $title,
        'start' => str_replace('', 'T', $row['DataInicio']),
        'end' => str_replace('', 'T', $row['DataFim']),
        'allDay' => false,
        'url' => $url,
        'color' => $status,
        'textColor' => $textColor,
        'TipoConsulta' => utf8_encode($row['TipoConsulta']),
        'Procedimento' => utf8_encode($row['Procedimento']),
        'Obs' => utf8_encode($row['Obs']),
        'Evento' => $row['Evento'],
    );
}

echo json_encode($event_array);
mysql_close($link);
?>
