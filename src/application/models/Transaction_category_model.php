<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // گرفتن لیست آیتم‌ها با جستجو و صفحه بندی
    public function get_transactions_categories($limit, $offset, $search = '') {
        if (!empty($search)) {
            $this->db->like('title', $search);
        }
        $query = $this->db->get('transaction_categories', $limit, $offset);
        return $query->result();
    }

    // شمارش کل رکوردها برای صفحه بندی
    public function count_transactions_categories($search = '') {
        if (!empty($search)) {
            $this->db->like('title', $search);
        }
        return $this->db->count_all_results('transaction_categories');
    }

    // گرفتن یک رکورد بر اساس id
    public function get_transaction_category($id) {
        return $this->db->get_where('transaction_categories', ['id' => $id])->row();
    }

    // افزودن تراکنش
    public function insert_transaction_category($data) {
        return $this->db->insert('transaction_categories', $data);
    }

    // بروزرسانی تراکنش
    public function update_transaction_category($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('transaction_categories', $data);
    }

    // حذف تراکنش
    public function delete_transaction_category($id) {
        $this->db->where('id', $id);
        return $this->db->delete('transaction_categories');
    }

    public function get_all() {
        return $this->db->get('transaction_categories')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('transaction_categories', ['id' => $id])->row();
    }

    
}
