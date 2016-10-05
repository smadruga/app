<?php

#controlador de Login

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Login_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('basico', 'form_validation', 'user_agent'));
        $this->load->driver('session');

        $_SESSION['Modulo'] = 'Sisbedam';
        $_SESSION['id_Modulo'] = 21;

        if ($this->agent->is_browser()) {

            if (
                    (preg_match("/(chrome|Firefox)/i", $this->agent->browser()) && $this->agent->version() < 30) ||
                    (preg_match("/(safari)/i", $this->agent->browser()) && $this->agent->version() < 6) ||
                    (preg_match("/(opera)/i", $this->agent->browser()) && $this->agent->version() < 12) ||
                    (preg_match("/(internet explorer)/i", $this->agent->browser()) && $this->agent->version() < 9 )
            ) {
                $msg = '<h2><a href="http://www.huap.uff.br/intranet"><strong>Navegador não suportado. <br>'
                        . 'Abra uma SA através do ramal 9424, ou clicando nesta mensagem, e solicite a atualização do seu '
                        . 'navegador de internet.</strong></a></h2>';

                echo $this->basico->erro($msg);
                exit();
            }
        }

    }

    public function index($modulo = NULL) {

        if ($this->Login_model->valid_modulo($modulo) == 0 && $modulo != 'login') show_404();
        
        ###################################################
        #só pra eu saber quando estou no banco de testes ou de produção
        $CI = & get_instance();
        $CI->load->database();
        if ($CI->db->database != 'sishuap')
            echo $CI->db->database;
        ###################################################
        #change error delimiter view
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        #Get GET or POST data
        $usuario = $this->input->get_post('usuario');
        $senha = md5($this->input->get_post('senha'));

        if ($modulo && preg_match("/sis\w+/", $modulo)) {
            $data['modulo'] = $modulo;
        }
        else {
            $data['modulo'] = $this->input->get_post('modulo');
        }

        #set validation rules
        $this->form_validation->set_rules('usuario', 'Usuário', 'required|trim|callback_valid_usuario');
        $this->form_validation->set_rules('senha', 'Senha', 'required|trim|md5|callback_valid_senha[' . $usuario . ']');
        #$this->form_validation->set_rules('senha', 'Senha', 'required|trim|md5|callback_valid_senha[usuario]');
        #load header view
        $this->load->view('sishuap/basico/headerlogin', $data);

        if ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Sua sessão expirou. Faça o login novamente.</strong>', 'erro', FALSE, FALSE, FALSE);
        else
            $data['msg'] = '';

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            #load login view
            $this->load->view('form_login', $data);
        }
        else {

            session_regenerate_id(true);

            if ($this->Login_model->check_ativo($usuario, $data['modulo']) === FALSE) {
                #$msg = "<strong>Senha</strong> incorreta ou <strong>usuário</strong> inexistente.";
                #$this->basico->erro($msg);
                $data['msg'] = $this->basico->msg('<strong>Usuário não possui autorização para acessar este módulo.</strong>', 'erro', FALSE, FALSE, FALSE);
                $this->load->view('form_login', $data);
            }
            else {
                #initialize session
                $this->load->driver('session');

                $query = $this->Login_model->get_usuario($usuario, $data['modulo']);
                
                $_SESSION['log']['usuario'] = $query['Usuario'];
                $_SESSION['log']['nivel'] = $query['Nivel'];
                $_SESSION['log']['id'] = $query['Id'];
                $_SESSION['log']['modulo'] = $data['modulo'];
                $_SESSION['log']['idmodulo'] = $query['Modulo'];
                
                if ($this->Login_model->set_acesso($_SESSION['log']['id'], 'LOGIN') === FALSE) {
                    $msg = "<strong>Erro no Banco de dados. Entre em contato com o Administrador.</strong>";

                    $this->basico->erro($msg);
                    $this->load->view('form_login');
                }
                else {
                    redirect($data['modulo'] . '/admin');
                }
            }
        }

        #load footer view
        $this->load->view('sishuap/basico/footerlogin');
        $this->load->view('sishuap/basico/footer');

    }

    public function sair($m = TRUE) {
        #change error delimiter view
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        #set logout in database
        if ($_SESSION['log'] && $m === TRUE) {
            $this->Login_model->set_acesso($_SESSION['log']['id'], 'LOGOUT');
        }
        else {
            if (!isset($_SESSION['log']['id'])) {
                $_SESSION['log']['id'] = 1;
            }
            $this->Login_model->set_acesso($_SESSION['log']['id'], 'TIMEOUT');
            $data['msg'] = '?m=2';
        }
        
        $modulo = $_SESSION['log']['modulo'];
        
        #clear de session data
        $this->session->unset_userdata('log');
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage

        /*
          #load header view
          $this->load->view('basico/headerlogin');

          $msg = "<strong>Você saiu do sistema.</strong>";

          $this->basico->alerta($msg);
          $this->load->view('login');
          $this->load->view('basico/footer');
         * 
         */

        redirect(base_url() . $modulo . '/' . $data['msg']);
        #redirect('login');

    }

    function valid_usuario($data) {

        if ($this->Login_model->check_usuario($data) == FALSE) {
            $this->form_validation->set_message('valid_usuario', '<strong>%s</strong> não existe.');
            return FALSE;
        }
        else if ($this->Login_model->check_usuario($data) == 1) {
            $this->form_validation->set_message('valid_usuario', '<strong>%s</strong> inativo.');
            return FALSE;
        }
        else {
            return TRUE;
        }

    }

    function valid_senha($senha, $usuario) {

        if ($this->Login_model->check_senha($senha, $usuario) == FALSE) {
            $this->form_validation->set_message('valid_senha', '<strong>%s</strong> senha incorreta.');
            return FALSE;
        }
        else {
            return TRUE;
        }

    }

}
