<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('Transaction_category_model', 'categories');
        // مدل Transaction دیگر به صورت CI_Model نیست، فقط کلاس PHP
        require_once(APPPATH.'models/Transaction.php');
    }

    public function index() {
        $this->load->library('pagination');

        $filters = [
            'title' => $this->input->get('title'),
            'type' => $this->input->get('type'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
        ];

        $config['base_url'] = site_url('transactions/index');
        $config['total_rows'] = Transaction::countFiltered($filters);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['attributes'] = ['class'=>'page-link'];
        $this->pagination->initialize($config);

        $offset = $this->input->get('per_page') ?? 0;

        $data['transactions'] = Transaction::paginate($config['per_page'], $offset, $filters);

        // اضافه کردن category_title به هر شی
        foreach($data['transactions'] as $t) {
            $category = $this->categories->get_by_id($t->category_id);
            $t->category_title = $category ? $category->title : '---';
        }

        $data['filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('transactions/index', $data);
    }

    public function create() {
        $data['categories'] = $this->categories->get_all();

        $this->form_validation->set_rules('title','عنوان','required');
        $this->form_validation->set_rules('amount','مبلغ','required|numeric');
        $this->form_validation->set_rules('type','نوع','required');
        $this->form_validation->set_rules('category_id','دسته بندی','required');
        $this->form_validation->set_rules('transaction_date','تاریخ','required');

        if($this->form_validation->run() === FALSE) {
            return $this->load->view('transactions/create', $data);
        }

        $transaction = new Transaction([
            'title'=>$this->input->post('title'),
            'amount'=>$this->input->post('amount'),
            'type'=>$this->input->post('type'),
            'category_id'=>$this->input->post('category_id'),
            'transaction_date'=>$this->input->post('transaction_date')
        ]);

        $transaction->save();
        redirect('transactions');
    }

    public function edit($id) {
        $transaction = Transaction::find($id);
        if(!$transaction) show_404();

        $data['transaction'] = $transaction;
        $data['categories'] = $this->categories->get_all();

        $this->form_validation->set_rules('title','عنوان','required');
        $this->form_validation->set_rules('amount','مبلغ','required|numeric');
        $this->form_validation->set_rules('type','نوع','required');
        $this->form_validation->set_rules('category_id','دسته بندی','required');
        $this->form_validation->set_rules('transaction_date','تاریخ','required');

        if($this->form_validation->run() === FALSE) {
            return $this->load->view('transactions/edit',$data);
        }

        $transaction->title = $this->input->post('title');
        $transaction->amount = $this->input->post('amount');
        $transaction->type = $this->input->post('type');
        $transaction->category_id = $this->input->post('category_id');
        $transaction->transaction_date = $this->input->post('transaction_date');

        $transaction->save();
        redirect('transactions');
    }

    public function delete($id) {
        $transaction = Transaction::find($id);
        if($transaction) $transaction->delete();
        redirect('transactions');
    }
}
