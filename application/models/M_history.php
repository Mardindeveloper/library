<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_history extends CI_Model
{

	public function get_history()
	{
		if ($this->session->userdata('level') == 'cashier') {
			return $this->db
				->join('user', 'user.user_id  = transaction.user_id ')
				->order_by('transaction_date', 'DESC')
				->where('transaction.user_id', $this->session->userdata('user_code'))
				->get('transaction')
				->result();
		}

		return $this->db
			->join('user', 'user.user_id  = transaction.user_id ')
			->join('transaction_detail', 'transaction.transaction_id  = transaction_detail.transaction_id ')
			->join('book', 'book.book_id  = transaction_detail.book_id ')
			->order_by('transaction_date', 'DESC')
			->get('transaction')
			->result();
	}

}

/* End of file M_history.php */
/* Location: ./application/models/M_history.php */
