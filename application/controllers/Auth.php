<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('AuthModel');

		$allowed_methods = ['logout'];
		$current_method = $this->router->fetch_method();

		if ($this->session->userdata('logged_in') && !in_array($current_method, $allowed_methods)) {
			redirect('dashboard');
		}
	}

	public function login()
	{
		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules([
				['field' => 'username', 'label' => 'username', 'rules' => 'trim|required'],
				['field' => 'password', 'label' => 'password', 'rules' => 'trim|required']
			]);

			if (!$this->form_validation->run()) {
				$this->session->set_flashdata('message', 'Username or Password must be filled!!');
				redirect('login');
			}

			$data = $this->input->post();
			if (!$this->AuthModel->checkLogin($data)) {
				$this->session->set_flashdata('message', 'Wrong Username and Password');
				redirect('login');
			}

			redirect('dashboard');
		}

		$this->load->view('Auth/login');
	}

	public function register()
	{
		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules([
				['field' => 'username', 'label' => 'username', 'rules' => 'trim|required'],
				['field' => 'password', 'label' => 'password', 'rules' => 'trim|required'],
				['field' => 'fullname', 'label' => 'fullname', 'rules' => 'trim|required'],
			]);

			if (!$this->form_validation->run()) {
				$this->session->set_flashdata('message', 'Username or Password must be filled!!');
				redirect('register');
			}

			$data = $this->input->post();
			unset($data['submit']);
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
			$data['level'] = 'customer';
			
			if (!$this->AuthModel->register($data)) {
				$this->session->set_flashdata('message', 'Wrong Username and Password');
				redirect('register');
			}

			redirect('login');
		}

		$this->load->view('Auth/register');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login', 'location', 302);
	}

}
