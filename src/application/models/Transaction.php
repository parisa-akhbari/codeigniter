<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction {

    public $id;
    public $title;
    public $amount;
    public $type;
    public $category_id;
    public $transaction_date;

    protected $CI;

    public function __construct($data = []) {
        // گرفتن instance CI
        $this->CI =& get_instance();
        $this->CI->load->database();

        // مقداردهی اولیه از آرایه
        foreach ($data as $key => $value) {
            if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /* ---------- Static Methods ---------- */

    public static function find($id) {
        $CI =& get_instance();
        $CI->load->database();
        $row = $CI->db->get_where('transactions', ['id' => $id])->row_array();
        return $row ? new Transaction($row) : null;
    }

    public static function all() {
        $CI =& get_instance();
        $CI->load->database();
        $rows = $CI->db->get('transactions')->result_array();
        return array_map(fn($r) => new Transaction($r), $rows);
    }

    public static function paginate($limit = 10, $offset = 0, $filters = []) {
        $CI =& get_instance();
        $CI->load->database();

        $CI->db->from('transactions');

        if(!empty($filters['title'])) {
            $CI->db->like('title', $filters['title']);
        }
        if(!empty($filters['type'])) {
            $CI->db->where('type', $filters['type']);
        }
        if(!empty($filters['start_date'])) {
            $CI->db->where('transaction_date >=', $filters['start_date']);
        }
        if(!empty($filters['end_date'])) {
            $CI->db->where('transaction_date <=', $filters['end_date']);
        }

        $CI->db->limit($limit, $offset);
        $rows = $CI->db->get()->result_array();
        return array_map(fn($r) => new Transaction($r), $rows);
    }

    public static function countFiltered($filters = []) {
        $CI =& get_instance();
        $CI->load->database();

        $CI->db->from('transactions');
        if(!empty($filters['title'])) $CI->db->like('title', $filters['title']);
        if(!empty($filters['type'])) $CI->db->where('type', $filters['type']);
        if(!empty($filters['start_date'])) $CI->db->where('transaction_date >=', $filters['start_date']);
        if(!empty($filters['end_date'])) $CI->db->where('transaction_date <=', $filters['end_date']);

        return $CI->db->count_all_results();
    }

    /* ---------- Instance Methods ---------- */

    public function save() {
        $data = [
            'title' => $this->title,
            'amount' => $this->amount,
            'type' => $this->type,
            'category_id' => $this->category_id,
            'transaction_date' => $this->transaction_date
        ];

        if(!empty($this->id)) {
            return $this->CI->db->update('transactions', $data, ['id' => $this->id]);
        }

        $this->CI->db->insert('transactions', $data);
        $this->id = $this->CI->db->insert_id();
        return true;
    }

    public function delete() {
        if(!empty($this->id)) {
            return $this->CI->db->delete('transactions', ['id' => $this->id]);
        }
        return false;
    }

}
