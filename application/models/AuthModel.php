<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthModel extends CI_Model
{

	public function checkLogin($data)
	{
		$user = $this->db->where('username', $data['username'])
			->get('user');


		if ($user->num_rows() === 1) {
			$dataUser = $user->row();

			if (password_verify($data['password'], $dataUser->password)) {
				$dataSession = [
					'logged_in' => true,
					"user_code" => $dataUser->user_id,
					'username' => $dataUser->username,
					'fullname' => $dataUser->fullname,
					'level' => $dataUser->level,
				];

				$this->session->set_userdata($dataSession);

				return true;
			}
		}
		return false;
	}

	public function register($data)
	{
		var_dump($data);
		$this->db->insert('user', $data);
		$result = $this->db->error();
		if ($result['code'] !== 0) {
			return false;
		}
		return true;
	}

	public function create_loan($data)
	{
		$this->db->trans_start();
		$this->db->insert('loan', $data);
		$this->db->trans_complete();

		$error = $this->db->error();
		if ($error['code'] !== 0) {
			$this->session
				->set_flashdata('error', $error['message']);
			return FALSE;
		}

		return TRUE;
	}

}
