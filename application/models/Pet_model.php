<?php

#modelo que verifica usu�rio e senha e loga o usu�rio no sistema, criando as sess�es necess�rias

defined('BASEPATH') OR exit('No direct script access allowed');

class Pet_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('basico');
    }

    public function set_pet($data) {

        $query = $this->db->insert('App_Paciente', $data);

        if ($this->db->affected_rows() === 0) {
            return FALSE;
        } else {
            #return TRUE;
            return $this->db->insert_id();
        }
    }

    public function get_pet($data) {
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

    public function update_pet($data, $id) {

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

    public function delete_pet($data) {
        $query = $this->db->delete('App_Paciente', array('idApp_Paciente' => $data));

        if ($this->db->affected_rows() === 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function lista_pet($data, $x) {

        $query = $this->db->query('SELECT * '
                . 'FROM App_Paciente WHERE '
                . 'idSis_Usuario = ' . $_SESSION['log']['id'] . ' AND '
                . '(NomePaciente like "%' . $data . '%" OR '
                #. 'DataNascimento = "' . $this->basico->mascara_data($data, 'mysql') . '" OR '
                . 'NomePet like "%' . $data . '%" OR '
                . 'PetDataNascimento = "' . $this->basico->mascara_data($data, 'mysql') . '" OR '
                . 'Telefone like "%' . $data . '%") '
                . 'ORDER BY NomePet ASC ');
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
                    $row->PetDataNascimento = $this->basico->mascara_data($row->PetDataNascimento, 'barras');
                }

                return $query;
            }
        }
    }

}
