<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthorModel extends CI_Model
{
	public function getAuthor($id = null)
	{
		$this->db
			->select('author.*, COUNT(book.book_id) AS total_books')
			->from('author')
			->join('book', 'book.author_id = author.author_id', 'left')
			->group_by(['author.author_id', 'author.name']);

		if ($id !== null) {
			$this->db->where('author.author_id', $id);
			return $this->db->get()->row();
		}

		return $this->db->get()->result();
	}

	public function saveAuthor($data, $id = '')
	{
		if (!empty($id)) {
			return $this->db->where('author_id', $id)
				->update('author', $data);
		}

		return $this->db->insert('author', $data);
	}
}
