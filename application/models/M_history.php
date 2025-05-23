<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_history extends CI_Model
{

	public function get_history()
	{
		if ($this->session->userdata('level') == 'cashier') {
			return $this->db
				->join('user', 'user.user_code = transaction.user_code')
				->order_by('tgl', 'DESC')
				->where('transaction.user_code', $this->session->userdata('user_code')) // 👈 مشخص شده
				->get('transaction')
				->result();
		}
		
		return $this->db->join('user', 'user.user_code = transaction.user_code')
			->order_by('tgl', 'DESC')
			->get('transaction')
			->result();
	}

}

/* End of file M_history.php */
/* Location: ./application/models/M_history.php */
