<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactionscategories extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Transaction_category_model', 'transaction_category_model');
        $this->load->helper('url');
        $this->load->library(['form_validation', 'pagination', 'session']);
    }

    /** صفحه لیست دسته‌بندی‌ها */
    public function index() {
		$user_id = $this->session->userdata('user_id');

		$filters = [
			'title' => $this->input->get('title'),
			'user_id' => $user_id
		];

		// Pagination Config
		$config['base_url'] = site_url('transactionscategories/index');
		$config['total_rows'] = $this->transaction_category_model->count_filtered($filters);
		$config['per_page'] = 10;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;

		// Bootstrap Pagination style
		$config['full_tag_open']  = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open']   = '<li class="page-item">';
		$config['num_tag_close']  = '</li>';
		$config['cur_tag_open']   = '<li class="page-item active"><a class="page-link">';
		$config['cur_tag_close']  = '</a></li>';
		$config['attributes']     = ['class' => 'page-link'];

		$this->pagination->initialize($config);

		$page = $this->input->get('page');
		$page = ($page) ? $page : 1;
		$offset = ($page - 1) * $config['per_page'];

		// Fetch categories
		$data['categories'] = $this->transaction_category_model->get_filtered_paginated(
			$filters,
			$config['per_page'],
			$offset
		);

		$data['filters'] = $filters;
		$data['pagination'] = $this->pagination->create_links();

		// اگر AJAX باشد
		if ($this->input->is_ajax_request()) {
			$this->load->view('categories/index', $data);
			return;
		}

		// Load view عادی
		$this->load->view('categories/index', $data);
}


    /** صفحه ایجاد */
    public function create() {

        $this->form_validation->set_rules('title', 'عنوان', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('categories/create');
        }

        $insert_data = [
            'title'   => $this->input->post('title'),
            'user_id' => $this->session->userdata('user_id')
        ];

        $this->transaction_category_model->insert($insert_data);

        redirect('transactionscategories');
    }

    /** صفحه ویرایش */
    public function edit($id) {

        $data['category'] = $this->transaction_category_model->get_by_id($id);

        if (!$data['category']) {
            show_404();
        }

        $this->form_validation->set_rules('title', 'عنوان', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('categories/edit', $data);
        }

        $update_data = [
            'title' => $this->input->post('title'),
        ];

        $this->transaction_category_model->update($id, $update_data);

        redirect('transactionscategories');
    }

    /** حذف */
    public function delete($id) {
        $this->transaction_category_model->delete($id);
        redirect('transactionscategories');
    }
}
