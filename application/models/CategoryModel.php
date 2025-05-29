<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CategoryModel extends CI_Model
{

	public function getCategory($id = '')
	{
		if ($id !== '') {
			return $this->db->where('category_id', $id)
				->get('book_category')
				->row();
		}
		return $this->db->get('book_category')->result();
	}

	public function saveCategory($data)
	{
		if (!empty($data['category_id']) && $data['category_id'] == 0) {
			return false;
		}

		if (!empty($data['category_id'])) {
			return $this->db->where('category_id', $data['category_id'])
				->update('book_category', $data);
		}

		return $this->db->insert('book_category', $data);
	}

	public function deleteCategory($id = '')
	{
		return $this->db->where('category_id', $id)
			->delete('book_category');
	}
}

/* End of file M_category.php */
/* Location: ./application/models/M_category.php */
