<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // گرفتن دسته‌بندی‌های کاربر با Pagination و جستجو
    public function get_user_categories($user_id, $limit = null, $offset = null, $search = '') {
    $this->db->where('user_id', $user_id);
    if(!empty($search)) $this->db->like('title', $search);
    $this->db->order_by('id','DESC');
    if($limit !== null) {
        $query = $this->db->get('transaction_categories', $limit, $offset);
    } else {
        $query = $this->db->get('transaction_categories');
    }
    return $query->result();
}



    // شمارش دسته‌بندی‌ها برای Pagination
    public function count_user_categories($user_id, $search = '') {
    $this->db->where('user_id', $user_id);
    if(!empty($search)) $this->db->like('title', $search);
    return $this->db->count_all_results('transaction_categories');
}



    // گرفتن یک دسته‌بندی
    public function get_category($id) {
        return $this->db->get_where('transaction_categories', ['id' => $id])->row();
    }

    // افزودن دسته‌بندی
    public function insert_category($data) {
        return $this->db->insert('transaction_categories', $data);
    }

    // بروزرسانی دسته‌بندی
    public function update_category($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('transaction_categories', $data);
    }

    // حذف دسته‌بندی
    public function delete_category($id) {
        $this->db->where('id', $id);
        return $this->db->delete('transaction_categories');
    }
}
