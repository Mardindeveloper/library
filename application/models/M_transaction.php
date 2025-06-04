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
		$type_transaction = $this->input->post('type_transaction');

		for ($i = 0; $i < count($this->input->post('rowid')); $i++) {
			$book_code = $this->input->post('book_code')[$i];
			if ($type_transaction == 'available_for_loan') {
				$qty = 1;
			} else {
				$qty = $this->input->post('book_qty')[$i];
			}

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
			'customer_id' => $this->input->post('customer_id'),
			'transaction_date ' => date('Y-m-d'),
			'total' => $this->input->post('total'),
			'type_transaction' => $type_transaction
		);
		$this->db->insert('transaction', $object);

		$tm_nota = $this->db->order_by('transaction_id', 'desc')
			->where('user_id', $this->input->post('user_id'))
			->limit(1)
			->get('transaction')
			->row();

		if ($type_transaction == 'available_for_loan') {
			$customerId = $this->input->post('customer_id');
			$totalLoan = $this->db->select('COUNT(*) as total_loan')
				->where('user_id', $customerId)
				->where('status', 'on_loan')
				->where('return_date IS NULL', null, false)
				->get('loan')
				->row()
				->total_loan;

			$totalLoan = (int) $totalLoan;
			$totalLoan += count($this->input->post('rowid'));
			if ($totalLoan > 4) {
				$this->db->where('user_id', $customerId)->update('user', ['can_loan' => 0]);
			}

			$loan = [];
			for ($i = 0; $i < count($this->input->post('rowid')); $i++) {
				$row = $this->db->select('copy_id')
					->where('book_id', $this->input->post('book_code')[$i])
					->where('status', 'available_for_loan')
					->get('book_copy')
					->row();

				if (empty($row) || empty($row->copy_id)) {
					$this->db->trans_rollback();
					return 0;
				}

				$copy_id = $row->copy_id;

				$loan[] = [
					'user_id' => $customerId,
					'copy_id' => $copy_id,
					'transaction_id' => $tm_nota->transaction_id,
					'loan_date' => date('Y-m-d'),
					'due_date' => date('Y-m-d', strtotime('+7 days')),
					'status' => 'on_loan'
				];
			}

			$proses = $this->db->insert_batch('loan', $loan);
			if (!$proses) {
				$error = $this->db->error();
				$this->db->trans_rollback();
				return $error;
			}

			$this->db->where('book_id', $book_code)->update('book_copy', ['status' => 'loaned']);
		} elseif ($type_transaction == 'for_sale') {
			$hasil = [];
			for ($i = 0; $i < count($this->input->post('rowid')); $i++) {
				$hasil[] = array(
					'transaction_id' => $tm_nota->transaction_id,
					'book_id' => $this->input->post('book_code')[$i],
					'quantity' => $this->input->post('book_qty')[$i]
				);
			}

			$proses = $this->db->insert_batch('transaction_detail', $hasil);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE || !$proses) {
			return 0;
		} else {
			return $tm_nota->transaction_id;
		}
	}

	public function detail_note($id_nota)
	{
		$transaction = $this->db->where('transaction_id', $id_nota)
			->join('user', 'user.user_id=transaction.user_id')
			->get('transaction')
			->row();

		$customer = $this->db
			->select('fullname as customer_name')
			->where('user_id', $transaction->customer_id)
			->get('user')
			->row()
			->customer_name;

		$transaction->customer_name = $customer ?? '';
		return $transaction;
	}

	public function detail_transaction($id_nota, $type_transaction)
	{
		if ($type_transaction == 'available_for_loan') {
			return $this->db->where('transaction_id', $id_nota)
				->join('book', 'book.book_id=loan.copy_id')
				->join('book_category', 'book_category.category_id=book.category_id')
				->get('loan')->result();
		}

		return $this->db->where('transaction_id', $id_nota)
			->join('book', 'book.book_id=transaction_detail.book_id')
			->join('book_category', 'book_category.category_id=book.category_id')
			->get('transaction_detail')->result();
	}

	public function get_transaction($transaction_code)
	{
		$this->db->where('transaction_id', $transaction_code);
		$transaction = $this->db->get('transaction')->row();

		if (!$transaction) {
			return false;
		}


		$user = $this->db->select('u.fullname as user_fullname, c.fullname as customer_fullname')
			->from('transaction t')
			->join('user u', 'u.user_id = t.user_id')            // کارمند
			->join('user c', 'c.user_id = t.customer_id')        // مشتری
			->where('t.transaction_id', $transaction->transaction_id)
			->get()
			->row();

		$transaction->user_fullname = $user ? $user->user_fullname : '';
		$transaction->customer_fullname = $user ? $user->customer_fullname : '';

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
			->where('book.stock >=', '1')
			->order_by('book.book_title', 'ASC')
			->get()
			->result_array();
	}

	public function getCustomer()
	{
		return $this->db
			->select('user_id, fullname')
			->where('level', 'customer')->get('user')->result();
	}

}

/* End of file M_transaction.php */
/* Location: ./application/models/M_transaction.php */
