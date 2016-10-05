<?php

#controlador de Login

defined('BASEPATH') OR exit('No direct script access allowed');

class Consulta extends CI_Controller {

    public function __construct() {
        parent::__construct();

        #load libraries
        $this->load->helper(array('form', 'url', 'date', 'string'));
        $this->load->library(array('basico', 'form_validation'));
        $this->load->model(array('Basico_model', 'Consulta_model', 'Paciente_model'));
        $this->load->driver('session');

        #load header view
        $this->load->view('basico/header');
        $this->load->view('basico/nav_principal');
    }

    public function index() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $this->load->view('consulta/tela_index', $data);

        #load footer view
        $this->load->view('basico/footer');
    }

    public function cadastrar($idApp_Paciente = NULL, $idApp_Agenda = NULL) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = quotes_to_entities($this->input->post(array(
                    'idApp_Consulta',
                    'idApp_Agenda',
                    'idApp_Paciente',
                    'Data',
                    'HoraInicio',
                    'HoraFim',
                    'idTab_TipoConsulta',
                    'Procedimento',
                    'Obs',
                        ), TRUE));

        if ($idApp_Paciente)
            $data['query']['idApp_Paciente'] = $idApp_Paciente;

        if ($_SESSION['agenda']) {
            $data['query']['Data'] = date('d/m/Y', $_SESSION['agenda']['HoraInicio']);
            $data['query']['HoraInicio'] = date('H:i', $_SESSION['agenda']['HoraInicio']);
            $data['query']['HoraFim'] = date('H:i', $_SESSION['agenda']['HoraFim']);
        }

        #if ($idApp_Agenda) 
        #$data['query']['$idApp_Agenda'] = $idApp_Agenda;
        #$data['query']['idApp_Agenda'] = 1;

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Data', 'Data', 'required|trim|valid_date');
        $this->form_validation->set_rules('HoraInicio', 'Hora Inicial', 'required|trim|valid_hour');
        $this->form_validation->set_rules('HoraFim', 'Hora Final', 'required|trim|valid_hour|valid_periodo_hora[' . $data['query']['HoraInicio'] . ']');
        $this->form_validation->set_rules('idTab_TipoConsulta', 'Tipo de Consulta', 'required|trim');

        $data['resumo'] = $this->Paciente_model->get_paciente($data['query']['idApp_Paciente']);

        $data['select']['TipoConsulta'] = $this->Basico_model->select_tipo_consulta();

        $data['titulo'] = 'Marcar Consulta - ' .  ucfirst($_SESSION['log']['cliente']) . ': ' . $data['resumo']['NomePaciente'];
        $data['form_open_path'] = 'consulta/cadastrar';
        $data['panel'] = 'primary';
        $data['metodo'] = 1;

        $data['datepicker'] = 'DatePicker';
        $data['timepicker'] = 'TimePicker';

        $data['tela'] = $this->load->view('consulta/form_consulta', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('paciente/tela_paciente', $data);
        } else {

            $data['query']['DataInicio'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraInicio'];
            $data['query']['DataFim'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraFim'];
            $data['query']['idTab_Status'] = 1;
            #$data['query']['idApp_Agenda'] = $_SESSION['log']['Agenda'];

            $data['redirect'] = '&gtd=' . $this->basico->mascara_data($data['query']['Data'], 'mysql');

            unset($data['query']['Data'], $data['query']['HoraInicio'], $data['query']['HoraFim']);

            $data['campos'] = array_keys($data['query']);
            $data['anterior'] = array();

            $data['idApp_Consulta'] = $this->Consulta_model->set_consulta($data['query']);

            if ($data['idApp_Consulta'] === FALSE) {
                $msg = "<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>";

                $this->basico->erro($msg);
                $this->load->view('consulta/form_consulta', $data);
            } else {

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['idApp_Consulta'], FALSE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Consulta', 'CREATE', $data['auditoriaitem']);
                $data['msg'] = '?m=1';

                //redirect(base_url() . 'paciente/prontuario/' . $data['query']['idApp_Paciente'] . $data['msg'] . $data['redirect']);
                redirect(base_url() . 'agenda' . $data['msg'] . $data['redirect']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function alterar($idApp_Paciente = FALSE, $idApp_Consulta = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Consulta',
            'idApp_Agenda',
            'idApp_Paciente',
            'Data',
            'HoraInicio',
            'HoraFim',
            'idTab_TipoConsulta',
            'idTab_Status',
            'Procedimento',
            'Obs',
                ), TRUE);

        if ($idApp_Paciente)
            $data['query']['idApp_Paciente'] = $idApp_Paciente;

        if ($idApp_Consulta) {
            $data['query']['idApp_Paciente'] = $idApp_Paciente;
            $data['query'] = $this->Consulta_model->get_consulta($idApp_Consulta);

            $dataini = explode(' ', $data['query']['DataInicio']);
            $datafim = explode(' ', $data['query']['DataFim']);

            $data['query']['Data'] = $this->basico->mascara_data($dataini[0], 'barras');
            $data['query']['HoraInicio'] = substr($dataini[1], 0, 5);
            $data['query']['HoraFim'] = substr($datafim[1], 0, 5);
        }

        if ($data['query']['DataFim'] < date('Y-m-d H:i:s', time())) {
            $data['readonly'] = 'readonly';
            $data['datepicker'] = '';
            $data['timepicker'] = '';
        } else {
            $data['readonly'] = '';
            $data['datepicker'] = 'DatePicker';
            $data['timepicker'] = 'TimePicker';
        }

        #echo $data['query']['DataInicio'];
        #$data['query']['idApp_Agenda'] = 1;

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Data', 'Data', 'required|trim|valid_date');
        $this->form_validation->set_rules('HoraInicio', 'Hora Inicial', 'required|trim|valid_hour');
        $this->form_validation->set_rules('HoraFim', 'Hora Final', 'required|trim|valid_hour|valid_periodo_hora[' . $data['query']['HoraInicio'] . ']');
        $this->form_validation->set_rules('idTab_TipoConsulta', 'Tipo de Consulta', 'required|trim');

        $data['select']['TipoConsulta'] = $this->Basico_model->select_tipo_consulta();
        $data['select']['Status'] = $this->Basico_model->select_status();

        $data['resumo'] = $this->Paciente_model->get_paciente($data['query']['idApp_Paciente']);

        $data['titulo'] = 'Editar Consulta - ' .  ucfirst($_SESSION['log']['cliente']) . ': ' . $data['resumo']['NomePaciente'];
        $data['form_open_path'] = 'consulta/alterar';
        #$data['readonly'] = '';
        #$data['disabled'] = '';
        $data['panel'] = 'primary';
        $data['metodo'] = 2;

        $data['tela'] = $this->load->view('consulta/form_consulta', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('paciente/tela_paciente', $data);
        } else {

            $data['query']['DataInicio'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraInicio'];
            $data['query']['DataFim'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraFim'];

            $data['redirect'] = '&gtd=' . $this->basico->mascara_data($data['query']['Data'], 'mysql');
            //exit();

            unset($data['query']['Data'], $data['query']['HoraInicio'], $data['query']['HoraFim']);

            $data['anterior'] = $this->Consulta_model->get_consulta($data['query']['idApp_Consulta']);
            $data['campos'] = array_keys($data['query']);

            $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['query']['idApp_Consulta'], TRUE);

            if ($data['auditoriaitem'] && $this->Consulta_model->update_consulta($data['query'], $data['query']['idApp_Consulta']) === FALSE) {
                $data['msg'] = '?m=2';
                redirect(base_url() . 'consulta/listar/' . $data['query']['idApp_Consulta'] . $data['msg']);
                exit();
            } else {

                if ($data['auditoriaitem'] === FALSE) {
                    $data['msg'] = '';
                } else {
                    $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Consulta', 'UPDATE', $data['auditoriaitem']);
                    $data['msg'] = '?m=1';
                }

                //redirect(base_url() . 'consulta/listar/' . $data['query']['idApp_Paciente'] . $data['msg'] . $data['redirect']);
                redirect(base_url() . 'agenda' . $data['msg'] . $data['redirect']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function listar($idApp_Paciente = NULL) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        if ($idApp_Paciente)
            $data['resumo'] = $this->Paciente_model->get_paciente($idApp_Paciente);

        $data['titulo'] = 'Listar Consultas - ' .  ucfirst($_SESSION['log']['cliente']) . ': ' . $data['resumo']['NomePaciente'];
        $data['panel'] = 'primary';
        $data['novo'] = '';
        $data['metodo'] = 4;

        $data['query'] = array();
        $data['proxima'] = $this->Consulta_model->lista_consulta_proxima($idApp_Paciente);
        $data['anterior'] = $this->Consulta_model->lista_consulta_anterior($idApp_Paciente);

        $data['tela'] = $this->load->view('consulta/list_consulta', $data, TRUE);

        $this->load->view('paciente/tela_paciente', $data);

        $this->load->view('basico/footer');
    }

    /*
     * Cadastrar/Alterar Eventos
     */

    public function cadastrar_evento($idApp_Paciente = NULL, $idApp_Agenda = NULL) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = quotes_to_entities($this->input->post(array(
                    'idApp_Consulta',
                    'idApp_Agenda',
                    'Data',
                    'HoraInicio',
                    'HoraFim',
                    'Evento',
                    'Obs',
                        ), TRUE));

        if ($this->input->get('start') && $this->input->get('end')) {
            $data['query']['Data'] = date('d/m/Y', substr($this->input->get('start'), 0, -3));
            $data['query']['HoraInicio'] = date('H:i', substr($this->input->get('start'), 0, -3));
            $data['query']['HoraFim'] = date('H:i', substr($this->input->get('end'), 0, -3));
        }

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Data', 'Data', 'required|trim|valid_date');
        $this->form_validation->set_rules('HoraInicio', 'Hora Inicial', 'required|trim|valid_hour');
        $this->form_validation->set_rules('HoraFim', 'Hora Final', 'required|trim|valid_hour|valid_periodo_hora[' . $data['query']['HoraInicio'] . ']');

        $data['titulo'] = 'Agendar Evento';
        $data['form_open_path'] = 'consulta/cadastrar_evento';
        $data['panel'] = 'primary';
        $data['metodo'] = 1;

        $data['datepicker'] = 'DatePicker';
        $data['timepicker'] = 'TimePicker';

        $data['tela'] = $this->load->view('consulta/form_evento', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('paciente/tela_paciente', $data);
        } else {

            $data['query']['DataInicio'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraInicio'];
            $data['query']['DataFim'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraFim'];

            $data['redirect'] = '&gtd=' . $this->basico->mascara_data($data['query']['Data'], 'mysql');

            unset($data['query']['Data'], $data['query']['HoraInicio'], $data['query']['HoraFim']);

            $data['campos'] = array_keys($data['query']);
            $data['anterior'] = array();

            $data['idApp_Consulta'] = $this->Consulta_model->set_consulta($data['query']);

            if ($data['idApp_Consulta'] === FALSE) {
                $msg = "<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>";

                $this->basico->erro($msg);
                $this->load->view('consulta/form_consulta', $data);
            } else {

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['idApp_Consulta'], FALSE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Consulta', 'CREATE', $data['auditoriaitem']);
                $data['msg'] = '?m=1';

                //redirect(base_url() . 'paciente/prontuario/' . $data['query']['idApp_Paciente'] . $data['msg'] . $data['redirect']);
                redirect(base_url() . 'agenda' . $data['msg'] . $data['redirect']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function alterar_evento($idApp_Consulta = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Consulta',
            'idApp_Agenda',
            'Data',
            'HoraInicio',
            'HoraFim',
            'Evento',
            'Obs',
                ), TRUE);


        if ($idApp_Consulta) {
            $data['query'] = $this->Consulta_model->get_consulta($idApp_Consulta);

            $dataini = explode(' ', $data['query']['DataInicio']);
            $datafim = explode(' ', $data['query']['DataFim']);

            $data['query']['Data'] = $this->basico->mascara_data($dataini[0], 'barras');
            $data['query']['HoraInicio'] = substr($dataini[1], 0, 5);
            $data['query']['HoraFim'] = substr($datafim[1], 0, 5);
        }

        if ($data['query']['DataFim'] < date('Y-m-d H:i:s', time())) {
            $data['readonly'] = 'readonly';
            $data['datepicker'] = '';
            $data['timepicker'] = '';
        } else {
            $data['readonly'] = '';
            $data['datepicker'] = 'DatePicker';
            $data['timepicker'] = 'TimePicker';
        }

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Data', 'Data', 'required|trim|valid_date');
        $this->form_validation->set_rules('HoraInicio', 'Hora Inicial', 'required|trim|valid_hour');
        $this->form_validation->set_rules('HoraFim', 'Hora Final', 'required|trim|valid_hour|valid_periodo_hora[' . $data['query']['HoraInicio'] . ']');

        $data['titulo'] = 'Agendar Evento';
        $data['form_open_path'] = 'consulta/alterar_evento';
        $data['panel'] = 'primary';
        $data['metodo'] = 2;

        $data['tela'] = $this->load->view('consulta/form_evento', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('paciente/tela_paciente', $data);
        } else {

            $data['query']['DataInicio'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraInicio'];
            $data['query']['DataFim'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraFim'];

            $data['redirect'] = '&gtd=' . $this->basico->mascara_data($data['query']['Data'], 'mysql');
            //exit();

            unset($data['query']['Data'], $data['query']['HoraInicio'], $data['query']['HoraFim']);

            $data['anterior'] = $this->Consulta_model->get_consulta($data['query']['idApp_Consulta']);
            $data['campos'] = array_keys($data['query']);

            $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['query']['idApp_Consulta'], TRUE);

            if ($data['auditoriaitem'] && $this->Consulta_model->update_consulta($data['query'], $data['query']['idApp_Consulta']) === FALSE) {
                $data['msg'] = '?m=2';
                redirect(base_url() . 'consulta/listar/' . $data['query']['idApp_Consulta'] . $data['msg']);
                exit();
            } else {

                if ($data['auditoriaitem'] === FALSE) {
                    $data['msg'] = '';
                } else {
                    $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Consulta', 'UPDATE', $data['auditoriaitem']);
                    $data['msg'] = '?m=1';
                }

                //redirect(base_url() . 'consulta/listar/' . $data['query']['idApp_Paciente'] . $data['msg'] . $data['redirect']);
                redirect(base_url() . 'agenda' . $data['msg'] . $data['redirect']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

}
