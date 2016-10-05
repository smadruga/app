<?php

#controlador de Login

defined('BASEPATH') OR exit('No direct script access allowed');

class Teste extends CI_Controller {

    public function __construct() {
        parent::__construct();

        #load libraries
        $this->load->helper(array('form', 'url', 'date', 'string'));
        $this->load->library(array('basico', 'form_validation'));
        $this->load->model(array('Basico_model'));
        $this->load->driver('session');

        #load header view
        $this->load->view('basico/header');
        $this->load->view('basico/nav_principal');
        
        unset($_SESSION['agenda']);

    }

    public function index() {

        $this->load->view('teste/tela_index', $data);

        #load footer view
        $this->load->view('basico/footer');
    }

}
