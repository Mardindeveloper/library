<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_transaction extends CI_Model
{

	public function tm_transaction()
	{
		return $this->db->get('user')->result();
	}
	public function cek($book_code)
	{
		$cek_stock = $this->db->where('book_code', $book_code)->get('book')->row()->stock;
		if ($cek_stock == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	public function check()
	{
		$cek = 1;
		for ($i = 0; $i < count($this->input->post('rowid')); $i++) {
			$stock = $this->db->where('book_code', $this->input->post('book_code')[$i])
				->get('book')
				->row()
				->stock;
			$qty = $this->input->post('qty')[$i];
			$sisa = $stock - $qty;
			if ($sisa < 0) {
				$oke = 0;
			} else {
				$oke = 1;
			}
			$cek = $oke * $cek;
		}
		return $cek;
	}

	public function save_cart_db()
	{
		$this->db->trans_start();

		for ($i = 0; $i < count($this->input->post('rowid')); $i++) {
			$book_code = $this->input->post('book_code')[$i];
			$qty = $this->input->post('qty')[$i];

			$book = $this->db->query("SELECT stock FROM book WHERE book_code = ? FOR UPDATE", [$book_code])->row();

			if (!$book) {
				$this->db->trans_rollback();
				return 0;
			}

			if ($book->stock < $qty) {
				$this->db->trans_rollback();
				return 0;
			}

			$new_stock = $book->stock - $qty;
			$this->db->where('book_code', $book_code)->update('book', ['stock' => $new_stock]);
		}

		$object = array(
			'user_code' => $this->input->post('user_code'),
			'buyer_name' => $this->input->post('buyer_name'),
			'tgl' => date('Y-m-d'),
			'total' => $this->input->post('total'),
			'bookname' => $this->input->post('bookname'),
			'book_qty' => $this->input->post('book_qty'),
		);
		$this->db->insert('transaction', $object);

		$tm_nota = $this->db->order_by('transaction_code', 'desc')
			->where('user_code', $this->input->post('user_code'))
			->limit(1)
			->get('transaction')
			->row();

		$hasil = [];
		for ($i = 0; $i < count($this->input->post('rowid')); $i++) {
			$hasil[] = array(
				'transaction_code' => $tm_nota->transaction_code,
				'book_code' => $this->input->post('book_code')[$i],
				'amount' => $this->input->post('qty')[$i]
			);
		}

		$proses = $this->db->insert_batch('transaction_detail', $hasil);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE || !$proses) {
			return 0;
		} else {
			return $tm_nota->transaction_code;
		}
	}


	public function detail_note($id_nota)
	{
		return $this->db->where('transaction_code', $id_nota)
			->join('user', 'user.user_code=transaction.user_code')
			->get('transaction')
			->row();
	}

	public function detail_transaction($id_nota)
	{
		return $this->db->where('transaction_code', $id_nota)
			->join('book', 'book.book_code=transaction_detail.book_code')
			->join('book_category', 'book_category.category_code=book.category_code')
			->get('transaction_detail')->result();
	}

	public function get_transaction($transaction_code)
	{
		$this->db->where('transaction_code', $transaction_code);
		$transaction = $this->db->get('transaction')->row();

		if (!$transaction)
			return false;

		// گرفتن اطلاعات کاربر (کَشیر)
		$this->db->where('user_code', $transaction->user_code);
		$user = $this->db->get('user')->row();
		$transaction->fullname = $user ? $user->fullname : '';

		return $transaction;
	}


}

/* End of file M_transaction.php */
/* Location: ./application/models/M_transaction.php */
