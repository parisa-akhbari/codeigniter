<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_category_model extends CI_Model {

    protected $table = 'transaction_categories';

    public function __construct() {
        parent::__construct();
    }
	
	public function get_all() {
        return $this->db
            ->select('transaction_categories.*')
            ->from($this->table)
            ->get()
            ->result();
    }

    public function get_by_id($id) {
        return $this->db
            ->select('transaction_categories.*')
            ->from($this->table)
            ->where('transaction_categories.id', $id)
            ->get()
            ->row();
    }

    public function insert(array $data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, array $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function get_filtered_paginated(array $filters = [], $limit = 10, $offset = 0) {

        $this->db->select('transaction_categories.*');
        $this->db->from($this->table);

        $this->apply_filters($filters);

        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_filtered(array $filters = []) {

        $this->db->from($this->table);

        $this->apply_filters($filters);

        return $this->db->count_all_results();
    }

    protected function apply_filters(array $filters) {

        if (!empty($filters['user_id'])) {
            $this->db->where('transaction_categories.user_id', $filters['user_id']);
        }

        if (!empty($filters['title'])) {
            $this->db->like('transaction_categories.title', $filters['title']);
        }
    }
}
