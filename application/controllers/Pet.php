<?php

#controlador de Login

defined('BASEPATH') OR exit('No direct script access allowed');

class Pet extends CI_Controller {

    public function __construct() {
        parent::__construct();

        #load libraries
        $this->load->helper(array('form', 'url', 'date', 'string'));
        #$this->load->library(array('basico', 'Basico_model', 'form_validation'));
        $this->load->library(array('basico', 'form_validation'));
        $this->load->model(array('Basico_model', 'Pet_model'));
        $this->load->driver('session');

        #load header view
        $this->load->view('basico/header');
        $this->load->view('basico/nav_principal');

        #$this->load->view('pet/nav_secundario');
    }

    public function index() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $this->load->view('pet/tela_index', $data);

        #load footer view
        $this->load->view('basico/footer');
    }

    public function cadastrar() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = quotes_to_entities($this->input->post(array(
            'idApp_Paciente',
            'RegistroFicha',
            'NomePaciente',
            'DataNascimento',
            'Telefone',
            'Sexo',
            'Endereco',
            'Bairro',
            'Municipio',
            'Obs',
            'idSis_Usuario',
            'Email',
            'NomePet',
            'PetDataNascimento',
            'PetSexo',
            'Especie',
            'Raca',
            'Pelo',
            'Cor',
        ), TRUE));

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        #$this->form_validation->set_rules('NomePaciente', 'Nome do Pet', 'required|trim|is_unique_duplo[App_Paciente.NomePaciente.DataNascimento.' . $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql') . ']');
        $this->form_validation->set_rules('NomePaciente', 'Nome do Responsável', 'required|trim');
        $this->form_validation->set_rules('DataNascimento', 'Data de Nascimento', 'trim|valid_date');
        $this->form_validation->set_rules('Email', 'E-mail', 'trim|valid_email');
        $this->form_validation->set_rules('NomePet', 'Nome do Pet', 'required|trim');
        $this->form_validation->set_rules('PetDataNascimento', 'Data de Nascimento do Pet', 'trim|valid_date');

        $data['select']['Municipio'] = $this->Basico_model->select_municipio();
        $data['select']['Sexo'] = $this->Basico_model->select_sexo();

        $data['titulo'] = 'Cadastrar ' . ucfirst($_SESSION['log']['cliente']);
        $data['form_open_path'] = 'pet/cadastrar';
        $data['readonly'] = '';
        $data['disabled'] = '';
        $data['panel'] = 'primary';
        $data['metodo'] = 1;

        $data['tela'] = $this->load->view('pet/form_pet', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('pet/tela_pet', $data);        
        } else {

            $data['query']['NomePaciente'] = mb_strtoupper($data['query']['NomePaciente']);
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql');
            $data['query']['Obs'] = nl2br($data['query']['Obs']);
            $data['query']['idSis_Usuario'] = $_SESSION['log']['id'];
            $data['query']['NomePet'] = mb_strtoupper($data['query']['NomePet']);
            $data['query']['PetDataNascimento'] = $this->basico->mascara_data($data['query']['PetDataNascimento'], 'mysql');

            $data['campos'] = array_keys($data['query']);
            $data['anterior'] = array();

            $data['idApp_Paciente'] = $this->Pet_model->set_pet($data['query']);

            if ($data['idApp_Paciente'] === FALSE) {
                $msg = "<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>";

                $this->basico->erro($msg);
                $this->load->view('pet/form_pet', $data);
            } else {

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['idApp_Paciente'], FALSE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Paciente', 'CREATE', $data['auditoriaitem']);
                $data['msg'] = '?m=1';

                redirect(base_url() . 'pet/prontuario/' . $data['idApp_Paciente'] . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function alterar($id = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Paciente',
            'RegistroFicha',
            'NomePaciente',
            'DataNascimento',
            'Telefone',
            'Sexo',
            'Endereco',
            'Bairro',
            'Municipio',
            'Obs',
            'idSis_Usuario',
            'Email',
            'NomePet',
            'PetDataNascimento',
            'PetSexo',
            'Especie',
            'Raca',
            'Pelo',
            'Cor',
        ), TRUE);

        if ($id) {
            $data['query'] = $this->Pet_model->get_pet($id);
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'barras');
            $data['query']['PetDataNascimento'] = $this->basico->mascara_data($data['query']['PetDataNascimento'], 'barras');
        }

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('NomePaciente', 'Nome do Responsável', 'required|trim');
        $this->form_validation->set_rules('DataNascimento', 'Data de Nascimento', 'trim|valid_date');
        $this->form_validation->set_rules('Email', 'E-mail', 'trim|valid_email');
        $this->form_validation->set_rules('NomePet', 'Nome do Pet', 'required|trim');
        $this->form_validation->set_rules('PetDataNascimento', 'Data de Nascimento do Pet', 'trim|valid_date');        

        $data['select']['Municipio'] = $this->Basico_model->select_municipio();
        $data['select']['Sexo'] = $this->Basico_model->select_sexo();

        $data['titulo'] = 'Editar dados do ' .  ucfirst($_SESSION['log']['cliente']) . ' - ' . $data['query']['NomePaciente'];
        $data['form_open_path'] = 'pet/alterar';
        $data['readonly'] = '';
        $data['disabled'] = '';
        $data['panel'] = 'primary';
        $data['metodo'] = 2;

        $data['resumo']['idApp_Paciente'] = $data['query']['idApp_Paciente'];
        $data['resumo']['NomePet'] = $data['query']['NomePet'];

        $data['tela'] = $this->load->view('pet/form_pet', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('pet/tela_pet', $data);
        } else {

            $data['query']['NomePaciente'] = mb_strtoupper($data['query']['NomePaciente']);
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql');
            $data['query']['Obs'] = nl2br($data['query']['Obs']);
            $data['query']['idSis_Usuario'] = $_SESSION['log']['id'];
            $data['query']['NomePet'] = mb_strtoupper($data['query']['NomePet']);
            $data['query']['PetDataNascimento'] = $this->basico->mascara_data($data['query']['PetDataNascimento'], 'mysql');
            

            $data['anterior'] = $this->Pet_model->get_pet($data['query']['idApp_Paciente']);
            $data['campos'] = array_keys($data['query']);

            $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['query']['idApp_Paciente'], TRUE);

            if ($data['auditoriaitem'] && $this->Pet_model->update_pet($data['query'], $data['query']['idApp_Paciente']) === FALSE) {
                $data['msg'] = '?m=2';
                redirect(base_url() . 'pet/form_pet/' . $data['query']['idApp_Paciente'] . $data['msg']);
                exit();
            } else {

                if ($data['auditoriaitem'] === FALSE) {
                    $data['msg'] = '';
                } else {
                    $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Paciente', 'UPDATE', $data['auditoriaitem']);
                    $data['msg'] = '?m=1';
                }

                redirect(base_url() . 'pet/prontuario/' . $data['query']['idApp_Paciente'] . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function excluir($id = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Paciente',
            'submit'
                ), TRUE);

        if ($id) {
            $data['query'] = $this->Pet_model->get_pet($id);
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'barras');
            $data['query']['PetDataNascimento'] = $this->basico->mascara_data($data['query']['PetDataNascimento'], 'barras');
        }

        $data['select']['Municipio'] = $this->Basico_model->select_municipio();
        $data['select']['Sexo'] = $this->Basico_model->select_sexo();

        $data['titulo'] = 'Tem certeza que deseja excluir o registro abaixo?';
        $data['form_open_path'] = 'pet/excluir';
        $data['readonly'] = 'readonly';
        $data['disabled'] = 'disabled';
        $data['panel'] = 'danger';
        $data['metodo'] = 3;

        $data['tela'] = $this->load->view('pet/form_pet', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('pet/tela_pet', $data); 
        } else {

            if ($data['query']['idApp_Paciente'] === FALSE) {
                $data['msg'] = '?m=2';
                $this->load->view('pet/form_pet', $data);
            } else {

                $data['anterior'] = $this->Pet_model->get_pet($data['query']['idApp_Paciente']);
                $data['campos'] = array_keys($data['anterior']);

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], NULL, $data['campos'], $data['query']['idApp_Paciente'], FALSE, TRUE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Paciente', 'DELETE', $data['auditoriaitem']);

                $this->Pet_model->delete_pet($data['query']['idApp_Paciente']);

                $data['msg'] = '?m=1';

                redirect(base_url() . 'pet' . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function pesquisar() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Pesquisa', 'Pesquisa', 'required|trim|callback_get_pet');
        
        if ($this->input->get('start') && $this->input->get('end')) {
            //$data['start'] = substr($this->input->get('start'),0,-3);
            //$data['end'] = substr($this->input->get('end'),0,-3);
            $_SESSION['agenda']['HoraInicio'] = substr($this->input->get('start'),0,-3);
            $_SESSION['agenda']['HoraFim'] = substr($this->input->get('end'),0,-3);            
        }
        
        $data['titulo'] = "Pesquisar Pet";
        $data['novo'] = '';

        $data['Pesquisa'] = $this->input->post('Pesquisa');
        //echo date('d/m/Y H:i:s', $data['start'],0,-3));
        
        #run form validation
        if ($this->form_validation->run() !== FALSE && $this->Pet_model->lista_pet($data['Pesquisa'], FALSE) === TRUE) {

            $data['query'] = $this->Pet_model->lista_pet($data['Pesquisa'], TRUE);

            if ($data['query']->num_rows() == 1) {
                $info = $data['query']->result_array();
                
                if ($_SESSION['agenda']) 
                    redirect('consulta/cadastrar/' . $info[0]['idApp_Paciente'] );
                else
                    redirect('pet/prontuario/' . $info[0]['idApp_Paciente'] );
                
                exit();
            } else {
                $data['list'] = $this->load->view('pet/list_pet', $data, TRUE);
            }
        }

        $this->load->view('pet/pesq_pet', $data);

        $this->load->view('basico/footer');
    }

    public function prontuario($id) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->Pet_model->get_pet($id);
        $data['titulo'] = 'Prontuário - ' .  ucfirst($_SESSION['log']['cliente']) . ': ' . $data['query']['NomePaciente'];
        $data['panel'] = 'primary';
        $data['metodo'] = 4;
        
        $data['resumo']['idApp_Paciente'] = $data['query']['idApp_Paciente'];
        $data['resumo']['NomePet'] = $data['query']['NomePet'];

        $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'barras');
        $data['query']['PetDataNascimento'] = $this->basico->mascara_data($data['query']['PetDataNascimento'], 'barras');
        $data['query']['Sexo'] = $this->Basico_model->get_sexo($data['query']['Sexo']);
        $data['query']['PetSexo'] = $this->Basico_model->get_sexo($data['query']['PetSexo']);

        if ($data['query']['Municipio']) {
            $mun = $this->Basico_model->get_municipio($data['query']['Municipio']);
            $data['query']['Municipio'] = $mun['NomeMunicipio'] . '/' . $mun['Uf'];
        } else {
            $data['query']['Municipio'] = $data['query']['Uf'] = $mun['Uf'] = '';
        }

        $data['tela'] = $this->load->view('pet/tela_resumo', $data, TRUE);

        $this->load->view('pet/tela_pet', $data);

        $this->load->view('basico/footer');
    }

    function get_pet($data) {

        if ($this->Pet_model->lista_pet($data, FALSE) === FALSE) {
            $this->form_validation->set_message('get_pet', '<strong>Dado</strong> não encontrado.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
