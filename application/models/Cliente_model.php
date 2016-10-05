<?php

#modelo que verifica usuário e senha e loga o usuário no sistema, criando as sessões necessárias

defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('basico');
    }

    public function set_cliente($data) {

        $query = $this->db->insert('App_Paciente', $data);

        if ($this->db->affected_rows() === 0) {
            return FALSE;
        } else {
            #return TRUE;
            return $this->db->insert_id();
        }
    }

    public function get_cliente($data) {
        $query = $this->db->query('SELECT * FROM App_Paciente WHERE idApp_Paciente = ' . $data);
        /*
          $query = $this->db->query(
          . 'SELECT '
          . 'P.NomePaciente, '
          . 'P.DataNascimento, '
          . 'P.Telefone, '
          . 'S.Sexo, '
          . 'P.Endereco, '
          . 'P.Bairro, '
          . 'M.NomeMunicipio AS Municipio, '
          . 'M.Uf, '
          . 'P.Obs, '
          . 'P.Email '
          . 'FROM '
          . 'App_Paciente AS P, '
          . 'Tab_Sexo AS S, '
          . 'Tab_Municipio AS M '
          . 'WHERE '
          . 'P.idApp_Paciente = ' . $data . ' AND '
          . 'P.Sexo = S.idTab_Sexo AND '
          . 'P.Municipio = M.idTab_Municipio'
          );
         * 
         */
        $query = $query->result_array();

        return $query[0];
    }

    public function update_cliente($data, $id) {

        unset($data['Id']);
        $query = $this->db->update('App_Paciente', $data, array('idApp_Paciente' => $id));
        /*
          echo $this->db->last_query();
          echo '<br>';
          echo "<pre>";
          print_r($query);
          echo "</pre>";
          exit ();
         */
        if ($this->db->affected_rows() === 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function delete_cliente($data) {
        $query = $this->db->delete('App_Paciente', array('idApp_Paciente' => $data));

        if ($this->db->affected_rows() === 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function lista_cliente($data, $x) {

        $query = $this->db->query('SELECT * '
                . 'FROM App_Paciente WHERE '
                . 'idSis_Usuario = ' . $_SESSION['log']['id'] . ' AND '
                . '(NomePaciente like "%' . $data . '%" OR '
                . 'Telefone like "%' . $data . '%") '
                . 'ORDER BY NomePaciente ASC ');
        /*
          echo $this->db->last_query();
          echo "<pre>";
          print_r($query);
          echo "</pre>";
          exit();
         */
        if ($query->num_rows() === 0) {
            return FALSE;
        } else {
            if ($x === FALSE) {
                return TRUE;
            } else {
                foreach ($query->result() as $row) {
                    $row->DataNascimento = $this->basico->mascara_data($row->DataNascimento, 'barras');
                }

                return $query;
            }
        }
    }

}
