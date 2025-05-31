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
			->select('book.*, author.name as author_name, book_category.category_name, book_copy.status,GROUP_CONCAT(book_copy.barcode) as barcodes')
			->from('book')
			->join('author', 'author.author_id = book.author_id')
			->join('book_category', 'book_category.category_id = book.category_id')
			->join('book_copy', 'book_copy.book_id = book.book_id', 'left')
			->where('book.is_loanable', 1)
			->group_by('book.book_id')
			->order_by('book.year', 'DESC');

		if ($id !== null) {
			$this->db->where('book.book_id', $id);
			return $this->db->get()->row();
		}

		return $this->db->get()->result();
	}

}

/* End of file M_book.php */
/* Location: ./application/models/M_book.php */
