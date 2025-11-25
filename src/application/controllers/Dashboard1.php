<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');  
        $this->load->model('User_model');

        // جلوگیری از دسترسی بدون لاگین
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/signup');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);

        $this->load->view('dashboard', $data);
    }
}


