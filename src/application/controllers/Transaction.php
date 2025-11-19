<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model');
        $this->load->helper(['url','form']);
        $this->load->library(['form_validation','session']);
    }

    // صفحه اصلی: نمایش فرم و جدول
    public function index() {
        $data['transactions'] = $this->Transaction_model->get_all();
        $this->load->view('transaction_page', $data);
    }

    // افزودن تراکنش
    public function store() {
        $this->form_validation->set_rules('title', 'Title', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $data = [
                'title' => $this->input->post('title')
            ];
            $this->Transaction_model->insert($data);
            $this->session->set_flashdata('success', 'Transaction added successfully.');
            redirect('transaction');
        }
    }

    // بروزرسانی تراکنش (AJAX)
    public function update($id) {
        $title = $this->input->post('title');
        if($title) {
            $this->Transaction_model->update($id, ['title'=>$title]);
            echo json_encode(['status'=>'success']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Title required']);
        }
    }

    // حذف تراکنش
    public function delete($id) {
        $this->Transaction_model->delete($id);
        $this->session->set_flashdata('success', 'Transaction deleted successfully.');
        redirect('transaction');
    }

    
}
