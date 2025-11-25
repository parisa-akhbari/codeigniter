<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactionscategories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_category_model');
        $this->load->library(['pagination','form_validation','session']);
        $this->load->helper(['url','form']);
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/signup');
        }
    }

    // لیست دسته‌بندی‌ها
    public function index() {
    $user_id = $this->session->userdata('user_id');
    $search = $this->input->get('search', TRUE) ?? '';
    $page = (int)$this->input->get('page');
    $page = ($page > 0) ? $page : 1;
    $per_page = 10;
    $offset = ($page - 1) * $per_page;

    $total_rows = $this->Transaction_category_model->count_user_categories($user_id, $search);

    $this->load->library('pagination');
    $config['base_url'] = site_url('transactionscategories?search=' . urlencode($search));
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul>';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['attributes'] = ['class'=>'page-link'];
    $this->pagination->initialize($config);

    $data['transactions'] = $this->Transaction_category_model->get_user_categories($user_id, $per_page, $offset, $search);
    $data['search'] = $search;
    $data['pagination'] = $this->pagination->create_links();

    $this->load->view('transactions_list', $data);
}


    // افزودن دسته‌بندی
    public function add() {
        $this->form_validation->set_rules('title','عنوان','required');

        if($this->form_validation->run() === TRUE) {
            $data = [
                'title' => $this->input->post('title'),
                'user_id' => $this->session->userdata('user_id')
            ];
            $this->Transaction_category_model->insert_category($data);
            redirect('transactionscategories');
        }

        $this->load->view('transaction_form',['action'=>'add','title'=>'']);
    }

    // ویرایش دسته‌بندی
    public function edit($id) {
        $category = $this->Transaction_category_model->get_category($id);
        if(!$category || $category->user_id != $this->session->userdata('user_id')) show_404();

        $this->form_validation->set_rules('title','عنوان','required');

        if($this->form_validation->run() === TRUE) {
            $data = ['title'=>$this->input->post('title')];
            $this->Transaction_category_model->update_category($id,$data);
            redirect('transactionscategories');
        }

        $this->load->view('transaction_form',['action'=>'edit','title'=>$category->title,'id'=>$category->id]);
    }

    // حذف دسته‌بندی
    public function delete($id) {
        $category = $this->Transaction_category_model->get_category($id);
        if($category && $category->user_id == $this->session->userdata('user_id')) {
            $this->Transaction_category_model->delete_category($id);
        }
        redirect('transactionscategories');
    }
}
