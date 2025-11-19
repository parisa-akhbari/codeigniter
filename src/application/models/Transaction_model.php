<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {

    protected $table = 'transactions';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // گرفتن همه تراکنش‌ها
    public function get_all() {
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    // افزودن تراکنش
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // گرفتن یک تراکنش
    public function get($id) {
        return $this->db->get_where($this->table, ['id'=>$id])->row();
    }

    // بروزرسانی تراکنش
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // حذف تراکنش
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
