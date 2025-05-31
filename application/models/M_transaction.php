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
		$cek_stock = $this->db->where('book_id', $book_code)->get('book')->row()->stock;
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
			$stock = $this->db->where('book_id', $this->input->post('book_code')[$i])
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

			$book = $this->db->query("SELECT stock FROM book WHERE book_id = ? FOR UPDATE", [$book_code])->row();

			if (!$book) {
				$this->db->trans_rollback();
				return 0;
			}

			if ($book->stock < $qty) {
				$this->db->trans_rollback();
				return 0;
			}

			$new_stock = $book->stock - $qty;
			$this->db->where('book_id', $book_code)->update('book', ['stock' => $new_stock]);
		}

		$object = array(
			'user_id' => $this->input->post('user_id'),
			'buyer_name' => $this->input->post('buyer_name'),
			'transaction_date ' => date('Y-m-d'),
			'total' => $this->input->post('total'),
		);
		$this->db->insert('transaction', $object);

		$tm_nota = $this->db->order_by('transaction_id', 'desc')
			->where('user_id', $this->input->post('user_id'))
			->limit(1)
			->get('transaction')
			->row();

		$hasil = [];
		for ($i = 0; $i < count($this->input->post('rowid')); $i++) {
			$hasil[] = array(
				'transaction_id' => $tm_nota->transaction_id,
				'book_id' => $this->input->post('book_code')[$i],
				'quantity' => $this->input->post('qty')[$i]
			);
		}

		$proses = $this->db->insert_batch('transaction_detail', $hasil);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE || !$proses) {
			return 0;
		} else {
			return $tm_nota->transaction_id;
		}
	}


	public function detail_note($id_nota)
	{
		return $this->db->where('transaction_id', $id_nota)
			->join('user', 'user.user_id=transaction.user_id')
			->get('transaction')
			->row();
	}

	public function detail_transaction($id_nota)
	{
		return $this->db->where('transaction_id', $id_nota)
			->join('book', 'book.book_id=transaction_detail.book_id')
			->join('book_category', 'book_category.category_id=book.category_id')
			->get('transaction_detail')->result();
	}

	public function get_transaction($transaction_code)
	{
		$this->db->where('transaction_id', $transaction_code);
		$transaction = $this->db->get('transaction')->row();

		if (!$transaction)
			return false;

		$this->db->where('user_id', $transaction->user_id);
		$user = $this->db->get('user')->row();
		$transaction->fullname = $user ? $user->fullname : '';

		return $transaction;
	}
	public function getBooksByType($type)
	{
		return $this->db
			->select('book.book_id, book.book_title, author.name as author_name, book_category.category_name, book.stock, book.price')
			->from('book')
			->join('author', 'author.author_id = book.author_id')
			->join('book_category', 'book_category.category_id = book.category_id')
			->where("EXISTS (
					SELECT 1 FROM book_copy 
					WHERE book_copy.book_id = book.book_id 
					AND book_copy.status = " . $this->db->escape($type) . "
				)", null, false)
			->order_by('book.book_title', 'ASC')
			->get()
			->result_array();

	}

}

/* End of file M_transaction.php */
/* Location: ./application/models/M_transaction.php */
