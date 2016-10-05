<?php

#modelo que verifica usuário e senha e loga o usuário no sistema, criando as sessões necessárias

defined('BASEPATH') OR exit('No direct script access allowed');

class Consulta_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('basico');
    }

    public function set_consulta($data) {

        $query = $this->db->insert('App_Consulta', $data);

        if ($this->db->affected_rows() === 0)
            return FALSE;
        else
            return $this->db->insert_id();
    }

    public function get_consulta($data) {
        $query = $this->db->query('SELECT * FROM App_Consulta WHERE idApp_Consulta = ' . $data);
        $query = $query->result_array();

        return $query[0];
    }

    public function update_consulta($data, $id) {

        unset($data['Id']);
        $query = $this->db->update('App_Consulta', $data, array('idApp_Consulta' => $id));
        /*
          echo $this->db->last_query();
          echo '<br>';
          echo "<pre>";
          print_r($query);
          echo "</pre>";
          exit ();
         */
        if ($this->db->affected_rows() === 0)
            return FALSE;
        else
            return TRUE;
    }

    public function delete_consulta($data) {
        $query = $this->db->delete('App_Consulta', array('idApp_Consulta' => $data));

        if ($this->db->affected_rows() === 0)
            return FALSE;
        else
            return TRUE;
    }

    public function lista_consulta_proxima($data) {

        $query = $this->db->query('SELECT '
                . 'C.idApp_Consulta, '
                . 'C.idApp_Agenda, '
                . 'C.idApp_Paciente, '
                . 'C.DataInicio, '
                . 'C.DataFim, '
                . 'TC.TipoConsulta, '
                . 'S.idTab_Status, '
                . 'S.Status, '
                . 'C.Procedimento, '
                . 'C.Obs '
                . 'FROM '
                . 'App_Consulta AS C, '
                . 'Tab_Status AS S, '
                . 'Tab_TipoConsulta AS TC '
                . 'WHERE '
                . 'C.idApp_Paciente = ' . $data . ' AND '
                . '(C.DataInicio >= "' . date('Y-m-d H:i:s', time()) . '" OR ('
                . 'C.DataInicio < "' . date('Y-m-d H:i:s', time()) . '" AND '
                . 'C.DataFim >= "' . date('Y-m-d H:i:s', time()) . '") ) AND '
                . 'C.idTab_Status = S.idTab_Status AND '
                . 'C.idTab_TipoConsulta = TC.idTab_TipoConsulta '
                . 'ORDER BY C.DataInicio ASC ');

        if ($query->num_rows() === 0)
            return FALSE;
        else
            return $query;
    }

    public function lista_consulta_anterior($data) {

        $query = $this->db->query('SELECT '
                . 'C.idApp_Consulta, '
                . 'C.idApp_Agenda, '
                . 'C.idApp_Paciente, '
                . 'C.DataInicio, '
                . 'C.DataFim, '
                . 'TC.TipoConsulta, '
                . 'S.idTab_Status, '
                . 'S.Status, '
                . 'C.Procedimento, '
                . 'C.Obs '
                . 'FROM '
                . 'App_Consulta AS C, '
                . 'Tab_Status AS S, '
                . 'Tab_TipoConsulta AS TC '
                . 'WHERE '
                . 'C.idApp_Paciente = ' . $data . ' AND '
                . 'C.DataFim < "' . date('Y-m-d H:i:s', time()) . '" AND '
                . 'C.idTab_Status = S.idTab_Status AND '
                . 'C.idTab_TipoConsulta = TC.idTab_TipoConsulta '
                . 'ORDER BY C.DataInicio ASC ');

        if ($query->num_rows() === 0)
            return FALSE;
        else
            return $query;
    }

}
