<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user extends CI_Model
{

	public function get_user()
	{
		$tm_user = $this->db->get('user')->result();
		return $tm_user;
	}
	public function save_user()
	{
		$object = array(
			'user_id' => $this->input->post('user_code'),
			'fullname' => $this->input->post('fullname'),
			'username' => $this->input->post('username'),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'level' => $this->input->post('level')
		);
		return $this->db->insert('user', $object);
	}
	public function detail($a)
	{
		return $this->db->where('user_id', $a)
			->get('user')
			->row();
	}
	public function edit_user()
	{
		$object = array(
			'fullname' => $this->input->post('fullname'),
			'username' => $this->input->post('username'),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'level' => $this->input->post('level')
		);
		return $this->db->where('user_id', $this->input->post('user_code_lama'))->update('user', $object);
	}
	public function hapus_user($id = '')
	{
		return $this->db->where('user_id', $id)->delete('user');
	}


	public function getCustomer()
	{
		return $this->db->where('level', 'customer')->get('user')->result();
	}

	public function getLoanCustomer($id)
	{
		return $this->db->select('loan.loan_date, loan.due_date, loan.return_date, loan.status, book.book_title as book_title')
			->from('loan')
			->join('book_copy', 'loan.copy_id = book_copy.copy_id')
			->join('book', 'book_copy.book_id = book.book_id')
			->where('user_id', $id)
			->get()
			->result();
	}

}

/* End of file M_user.php */
/* Location: ./application/models/M_user.php */
