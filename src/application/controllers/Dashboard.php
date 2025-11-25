<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	private function getUserData()
{
    return (object)[
        'id'       => $this->session->userdata('user_id'),
        'username' => $this->session->userdata('username'),
		'profile_image' => $this->session->userdata('profile_image'),
    ];
}

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
		$this->load->model('User_model');
		$this->load->model('Transaction_model');
		$this->load->model('Transaction_category_model');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/signup');
        }
    }

   public function index()
{
    $data['user'] = $this->getUserData();
    $this->load->view('dashboard', $data);
}
	
	//public function profile()
//{
    //$user_id = $this->session->userdata('user_id');
    //$data['user'] = $this->User_model->get_user_by_id($user_id);
    //$this->load->view('profile', $data);
//}
	
	//public function transactions()
//{
    //$user_id = $this->session->userdata('user_id');

    // گرفتن اطلاعات کاربر
    //$data['user'] = $this->User_model->get_user_by_id($user_id);

    // گرفتن لیست تراکنش‌های همین کاربر
    //$data['transactions'] = $this->Transaction_model->get_user_transactions($user_id);

    // لود صفحه تراکنش‌ها
    //$this->load->view('transactions/index', $data);
//}


	public function load_page($page)
{
    $user_id = $this->session->userdata('user_id');

    if ($page == "home") {
        $this->load->view("dashboard");
    }
    elseif ($page == "profile") {
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $this->load->view("profile", $data);
    }
    elseif ($page == "transactions") {
        $this->load->library('pagination');

        // گرفتن فیلترها از GET
        $filters = [
            'user_id' => $user_id,
            'title' => $this->input->get('title'),
            'type' => $this->input->get('type'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date')
        ];

        // Pagination Config
        $config['base_url'] = site_url('Dashboard/load_page/transactions');
        $config['total_rows'] = $this->Transaction_model->count_filtered($filters);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;

        // Bootstrap 5 pagination
        $config['full_tag_open']  = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open']   = '<li class="page-item">';
        $config['num_tag_close']  = '</li>';
        $config['cur_tag_open']   = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close']  = '</a></li>';
        $config['attributes']     = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        // صفحه جاری
        $page_num = (int) $this->input->get('page');
        $page_num = ($page_num > 0) ? $page_num : 1;
        $offset = ($page_num - 1) * $config['per_page'];

        // گرفتن تراکنش‌ها با Pagination
        $data['transactions'] = $this->Transaction_model->get_filtered_paginated($filters, $config['per_page'], $offset);
        $data['filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view("transactions/index", $data);
    }
     elseif ($page == "categories") {
            $search = $this->input->get('search') ?? '';
            $page_num = (int)$this->input->get('page'); 
            $page_num = ($page_num > 0) ? $page_num : 1;
            $per_page = 10;
            $offset = ($page_num - 1) * $per_page;

            $total_rows = $this->Transaction_category_model->count_user_categories($user_id, $search);

            $this->load->library('pagination');
            $config['base_url'] = site_url('Dashboard/load_page/categories?search=' . urlencode($search));
            $config['total_rows'] = $total_rows;
            $config['per_page'] = $per_page;
            $config['page_query_string'] = TRUE;
            $config['query_string_segment'] = 'page';
            $config['use_page_numbers'] = TRUE;
            $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
            $config['full_tag_close'] = '</ul>';
            $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['attributes'] = ['class' => 'page-link'];
            $this->pagination->initialize($config);

            $data['transactions'] = $this->Transaction_category_model->get_user_categories($user_id, $per_page, $offset, $search);
            $data['search'] = $search;
            $data['pagination'] = $this->pagination->create_links();
            $this->load->view('transactions_list', $data);
        }


    else {
        echo "<p>صفحه یافت نشد.</p>";
    }
}


	


}
