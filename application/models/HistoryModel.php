<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryModel extends CI_Model
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
			->select('transaction.*,
				u.fullname as user_fullname,
				c.fullname as customer_fullname,
				book.book_title, transaction_detail.quantity')
			->from('transaction')
			->join('user u', 'u.user_id = transaction.user_id ')
			->join('user c', 'c.user_id = transaction.customer_id ')
			->join('transaction_detail', 'transaction_detail.transaction_id = transaction.transaction_id ')
			->join('book', 'book.book_id = transaction_detail.book_id ')
			->order_by('transaction_date', 'DESC')
			->get()
			->result();
	}

}
