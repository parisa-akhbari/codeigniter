<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Transaction_model', 'transactions_model');
        $this->load->model('Transaction_category_model', 'categories_model');
        $this->load->helper('url');
        $this->load->library(['form_validation', 'pagination']);
    }

    /** صفحه لیست تراکنش‌ها */
    public function index() {
		$user_id = $this->session->userdata('user_id');

		$filters = [
			'title' => $this->input->get('title'),
			'type' => $this->input->get('type'),
			'start_date' => $this->input->get('start_date'),
			'end_date' => $this->input->get('end_date'),
			'user_id' => $user_id
		];

		// Pagination Config
		$config['base_url'] = site_url('transactions/index');
		$config['total_rows'] = $this->transactions_model->count_filtered($filters);
		$config['per_page'] = 10;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page'; // این پارامتر مهم است
		$config['use_page_numbers'] = TRUE;

		// Bootstrap 5 pagination style
		$config['full_tag_open']  = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open']   = '<li class="page-item">';
		$config['num_tag_close']  = '</li>';
		$config['cur_tag_open']   = '<li class="page-item active"><a class="page-link">';
		$config['cur_tag_close']  = '</a></li>';
		$config['attributes']     = ['class' => 'page-link'];

		$this->pagination->initialize($config);

		// Page number from query string
		$page = $this->input->get('page');
		$page = ($page) ? $page : 1;
		$offset = ($page - 1) * $config['per_page'];

		// Fetch transactions
		$data['transactions'] = $this->transactions_model->get_filtered_paginated(
			$filters,
			$config['per_page'],
			$offset
		);

		$data['filters'] = $filters;
		$data['pagination'] = $this->pagination->create_links();

		// اگر درخواست AJAX باشد، فقط view را لود کن
		if($this->input->is_ajax_request()){
			$this->load->view('transactions/index', $data);
		} else {
			$this->load->view('transactions/index', $data);
		}
	}



    /** صفحه ایجاد تراکنش جدید */
    public function create() {
        $data['categories'] = $this->categories_model->get_all();

        $this->form_validation->set_rules('title', 'عنوان', 'required');
        $this->form_validation->set_rules('amount', 'مبلغ', 'required|numeric');
        $this->form_validation->set_rules('type', 'نوع', 'required');
        $this->form_validation->set_rules('category_id', 'دسته بندی', 'required');
        $this->form_validation->set_rules('transaction_date', 'تاریخ', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('transactions/create', $data);
        }

        $insert_data = [
            'title' => $this->input->post('title'),
            'amount' => $this->input->post('amount'),
            'type' => $this->input->post('type'),
            'category_id' => $this->input->post('category_id'),
            'transaction_date' => $this->input->post('transaction_date'),
			'user_id' => $this->session->userdata('user_id')
        ];

        $this->transactions_model->insert($insert_data);
        redirect('transactions');
    }

    /** صفحه ویرایش تراکنش */
    public function edit($id) {
        $data['transaction'] = $this->transactions_model->get_by_id($id);
        if (!$data['transaction']) show_404();

        $data['categories'] = $this->categories_model->get_all();

        $this->form_validation->set_rules('title', 'عنوان', 'required');
        $this->form_validation->set_rules('amount', 'مبلغ', 'required|numeric');
        $this->form_validation->set_rules('type', 'نوع', 'required');
        $this->form_validation->set_rules('category_id', 'دسته بندی', 'required');
        $this->form_validation->set_rules('transaction_date', 'تاریخ', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('transactions/edit', $data);
        }

        $update_data = [
            'title' => $this->input->post('title'),
            'amount' => $this->input->post('amount'),
            'type' => $this->input->post('type'),
            'category_id' => $this->input->post('category_id'),
            'transaction_date' => $this->input->post('transaction_date'),
        ];

        $this->transactions_model->update($id, $update_data);
        redirect('transactions');
    }

    /** حذف تراکنش */
    public function delete($id) {
        $this->transactions_model->delete($id);
        redirect('transactions');
    }
}
