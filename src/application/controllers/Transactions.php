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

        $this->load->helper("jdf");

        // $now = new Datetime();

        // echo jdate("Y/m/d", $now->getTimestamp(), '', "Asia/Tehran", "en");

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
        $this->load->helper("jdf");
        $data['categories'] = $this->categories_model->get_all();

        $this->form_validation->set_rules('title', 'عنوان', 'required');
        $this->form_validation->set_rules('amount', 'مبلغ', 'required|numeric');
        $this->form_validation->set_rules('type', 'نوع', 'required');
        $this->form_validation->set_rules('category_id', 'دسته بندی', 'required');
        $this->form_validation->set_rules('transaction_date', 'تاریخ', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('transactions/create', $data);
        }

        // گرفتن تاریخ شمسی از فرم
        $persianDatetime = $this->input->post('transaction_date'); // مثال: 1402-09-15 یا 1402/09/15

        // 1. تبدیل اعداد فارسی به انگلیسی (اگر کاربر فارسی وارد کرده باشد)
        $persianDatetime = strtr($persianDatetime, [
            '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9'
        ]);

        // 2. جایگزینی / با - اگر لازم باشد
        $persianDatetime = str_replace('/', '-', $persianDatetime);

        // 3. explode برای گرفتن سال، ماه، روز
        list($jy, $jm, $jd) = explode('-', $persianDatetime);

        // 4. تبدیل شمسی به میلادی
        list($gy, $gm, $gd) = jalali_to_gregorian($jy, $jm, $jd);
        $gregorianDate = sprintf("%04d-%02d-%02d", $gy, $gm, $gd);

        // آماده سازی آرایه برای insert
        $insert_data = [
            'title' => $this->input->post('title'),
            'amount' => $this->input->post('amount'),
            'type' => $this->input->post('type'),
            'category_id' => $this->input->post('category_id'),
            'transaction_date' => $gregorianDate, // اکنون میلادی است
            'user_id' => $this->session->userdata('user_id')
        ];

        //log_message("debug", "Insert data: " . print_r($insert_data, true));

        $this->transactions_model->insert($insert_data);
        redirect('transactions');
}

    /** صفحه ویرایش تراکنش */
    public function edit($id) {
        $this->load->helper("jdf");

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

        // گرفتن تاریخ شمسی از فرم
        $persianDatetime = $this->input->post('transaction_date'); // مثال: 1402-09-15 یا 1402/09/15

        // 1. تبدیل اعداد فارسی به انگلیسی
        $persianDatetime = strtr($persianDatetime, [
            '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9'
        ]);

        // 2. جایگزینی / با -
        $persianDatetime = str_replace('/', '-', $persianDatetime);

        // 3. explode برای گرفتن سال، ماه، روز
        list($jy, $jm, $jd) = explode('-', $persianDatetime);

        // 4. تبدیل شمسی به میلادی
        list($gy, $gm, $gd) = jalali_to_gregorian($jy, $jm, $jd);
        $gregorianDate = sprintf("%04d-%02d-%02d", $gy, $gm, $gd);

        // آماده سازی آرایه برای update
        $update_data = [
            'title' => $this->input->post('title'),
            'amount' => $this->input->post('amount'),
            'type' => $this->input->post('type'),
            'category_id' => $this->input->post('category_id'),
            'transaction_date' => $gregorianDate, // اکنون میلادی است
        ];

        log_message("debug", "Update data: " . print_r($update_data, true));

        $this->transactions_model->update($id, $update_data);
        redirect('transactions');
}


    /** حذف تراکنش */
    public function delete($id) {
        $this->transactions_model->delete($id);
        redirect('transactions');
    }
}
