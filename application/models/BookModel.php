<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BookModel extends CI_Model
{
	public function getBooks($id = null)
	{
		$this->db->join('book_category', 'book_category.category_id = book.category_id');
		$this->db->join('author', 'author.author_id = book.author_id');
		$this->db->order_by('year', 'DESC');

		if ($id !== null) {
			$this->db->where('book.book_id', $id);
			return $this->db->get('book')->row();
		}

		return $this->db->get('book')->result();
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
		if ($name_file !== "") {
			$data['book_img'] = $name_file;
		}

		if (!empty($id)) {
			return $this->db->where('book_id', $id)
				->update('book', $data);
		}

		return $this->db->insert('book', $data);
	}
	public function deleteBook($book_id = '')
	{
		return $this->db->where('book_id', $book_id)->delete('book');
	}

}

/* End of file M_book.php */
/* Location: ./application/models/M_book.php */
