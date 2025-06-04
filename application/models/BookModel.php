<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BookModel extends CI_Model
{
	public function getBooks($id = null)
	{
		$this->db
			->select('book.*, author.name as author_name, book_category.category_name, book_copy.status,GROUP_CONCAT(book_copy.barcode) as barcodes')
			->from('book')
			->join('author', 'author.author_id = book.author_id')
			->join('book_category', 'book_category.category_id = book.category_id')
			->join('book_copy', 'book_copy.book_id = book.book_id', 'left')
			->group_by('book.book_id')
			->order_by('book.year', 'DESC');

		if ($id !== null) {
			$this->db->where('book.book_id', $id);
			return $this->db->get()->row();
		}

		return $this->db->get()->result();
	}
	public function getCategory()
	{
		return $this->db->get('book_category')->result();
	}
	public function getAuthor()
	{
		return $this->db->get('author')->result();
	}
	public function saveBook($data, $name_file = '', $id = '')
	{
		if (!isset($data['status'])) {
			$statusBook = 'for_sale';
		} else {
			$statusBook = $data['status'];
			unset($data['status']);
		}

		if (isset($data['barcode']) && !empty($data['barcode'])) {
			$barcodeBook = $data['barcode'];
			unset($data['barcode']);
		} else {
			return false;
		}

		if ($name_file !== "") {
			$data['book_img'] = $name_file;
		}

		$this->db->trans_begin();

		if (!empty($id)) {
			$this->db->where('book_id', $id)
				->update('book', $data);
		} else {
			$this->db->insert('book', $data);
			$id = $this->db->insert_id();
		}

		if ($id) {
			$exists = $this->db->get_where('book_copy', ['book_id' => $id])->row();

			if ($exists) {
				$this->db->where('book_id', $id)
					->update('book_copy', [
						'barcode' => $barcodeBook,
						'status' => $statusBook,
					]);
			} else {
				$this->db->insert('book_copy', [
					'book_id' => $id,
					'status' => $statusBook,
					'barcode' => $barcodeBook
				]);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	public function deleteBook($book_id = '')
	{
		return $this->db->where('book_id', $book_id)->delete('book');
	}

	public function getBookLoan($id = null)
	{
		$this->db
			->select('loan.*, book.book_title as book_title, book_category.category_name')
			->from('loan')
			->join('book_copy', 'book_copy.copy_id = loan.copy_id')
			->join('book', 'book.book_id = book_copy.book_id ')
			->join('book_category', 'book_category.category_id = book.category_id')
			->order_by('return_date', 'ASC');

		if ($id !== null) {
			$this->db->where('loan.loan_id', $id);
			return $this->db->get()->row();
		}

		return $this->db->get()->result();
	}

	public function saveLoan($loan_id, $return_date, $user_id)
	{
		$this->db->where('loan_id', $loan_id);
		$this->db->set('return_date', $return_date);
		$this->db->set('status', 'returned');
		$status = $this->db->update('loan');

		if ($status) {
			$copy_id = $this->db->select('copy_id')->where('loan_id', $loan_id)->get('loan')->row()->copy_id;
			$this->db->where('copy_id', $copy_id);
			$this->db->set('status', 'available_for_loan');
			$this->db->update('book_copy');

			$book_id = $this->db->select('book_id')->where('copy_id', $copy_id)->get('book_copy')->row()->book_id;
			$this->db->where('book_id', $book_id);
			$this->db->set('stock', 1);
			$this->db->update('book');

			$totalLoan = $this->db->select('COUNT(*) as total_loan')
				->where('user_id', $user_id)
				->where('status', 'on_loan')
				->where('return_date IS NULL', null, false)
				->get('loan')
				->row()
				->total_loan;

			if ($totalLoan <= 4) {
				$this->db->where('user_id', $user_id);
				$this->db->set('can_loan', 1);
				$this->db->update('user');
			}

			return true;
		}
		return false;
	}

}

/* End of file M_book.php */
/* Location: ./application/models/M_book.php */
