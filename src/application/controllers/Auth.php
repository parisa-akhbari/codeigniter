<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->database();
        $this->load->helper(['url', 'security']);
    }

    public function signup() {
        // قوانین اعتبارسنجی فرم
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        // $this->form_validation->set_rules('fullname', 'Full Name', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('auth/signup');
        } else {
            // ذخیره اطلاعات در دیتابیس
            $data = [
                'username' => $this->input->post('username', true),
                'password' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
                // 'fullname' => $this->input->post('fullname', true)
            ];

            $this->db->insert('users', $data);
            $this->session->set_flashdata('message', 'ثبت نام با موفقیت انجام شد. لطفا وارد شوید.');
            redirect('auth/signup');
        }
    }

    public function login() {

        // اگر کاربر قبلاً لاگین کرده باشد
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($this->input->post()) {

            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User_model->get_user($username);

            if ($user && password_verify($password, $user->password)) {

                $userdata = [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'logged_in' => TRUE
                ];

                $this->session->set_userdata($userdata);

                redirect('profile');

            } else {
                $data['error'] = "نام کاربری یا رمز عبور اشتباه است.";
            }
        }

        $this->load->view('auth/signup');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/signup');
    }
}

