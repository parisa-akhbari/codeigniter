<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactionscategories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_category_model');
        $this->load->library('pagination');
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');
    }

    // لیست تراکنش‌ها
    public function index() {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
    // دریافت مقدار جستجو
        $search = $this->input->get('search', TRUE) ?? '';

        // دریافت شماره صفحه و اطمینان از اینکه رشته و عدد است
        $page = $this->input->get('page', TRUE); // ممکن است null باشد
        if ($page === null || !ctype_digit($page)) {
            $page = '0'; // مقدار پیش‌فرض
        }

        // تبدیل به عدد صحیح برای استفاده در query
        $page_int = (int) $page;

        // شمارش کل رکوردها
        $total_rows = (int) $this->Transaction_category_model->count_transactions_categories($search);

        // تنظیمات Pagination
        $config = array();
        $config['base_url'] = site_url('transactionscategories?search=' . urlencode($search));
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        // استایل Bootstrap 5
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = 'اول';
        $config['last_link'] = 'آخر';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        // دریافت داده‌ها
        $data['search'] = $search;
        $data['transactions'] = $this->Transaction_category_model->get_transactions_categories($config['per_page'], $page_int, $search);

        // ایجاد لینک‌های Pagination
        $data['pagination'] = $this->pagination->create_links();

        // بارگذاری ویو
        $this->load->view('transactions_list', $data);
    }

    // افزودن تراکنش
    public function add() {
        $this->form_validation->set_rules('title', 'عنوان', 'required');

        if ($this->form_validation->run() === TRUE) {
            $data = ['title' => $this->input->post('title')];
            $this->Transaction_category_model->insert_transaction_category($data);
            redirect('transactionscategories');
        }

        $this->load->view('transaction_form', ['action' => 'add', 'title' => '']);
    }

    // ویرایش تراکنش
    public function edit($id) {
        $transaction = $this->Transaction_category_model->get_transaction_category($id);
        if (!$transaction) show_404();

        $this->form_validation->set_rules('title', 'عنوان', 'required');

        if ($this->form_validation->run() === TRUE) {
            $data = ['title' => $this->input->post('title')];
            $this->Transaction_category_model->update_transaction_category($id, $data);
            redirect('transactionscategories');
        }

        $this->load->view('transaction_form', ['action' => 'edit/' . $id, 'title' => $transaction->title]);
    }

    // حذف تراکنش
    public function delete($id) {
        $transaction = $this->Transaction_category_model->get_transaction_category($id);
        if ($transaction) {
            $this->Transaction_category_model->delete_transaction_category($id);
        }
        redirect('transactionscategories');
    }
}
