<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardModel extends CI_Model
{
	public function countTableRows($table, $where = null)
	{
		$this->db->select('count(*) as total');
		if ($where) {
			$this->db->where($where);
		}
		return $this->db->get($table)->row()->total;
	}

	public function sumTableColumn($table, $column, $where = null)
	{
		$this->db->select("SUM($column) as total");
		if ($where) {
			$this->db->where($where);
		}
		return $this->db->get($table)->row()->total;
	}

	public function countTransactionsByUserLevel()
	{
		if ($this->session->userdata('level') == 'cashier') {
			return $this->db->select('count(*) as transactions')
				->where('user_code', $this->session->userdata('user_code'))
				->get('transaction')
				->row()
				->transactions;
		}
		return $this->db->select('count(*) as transactions')
			->get('transaction')
			->row()
			->transactions;
	}

}
?>
