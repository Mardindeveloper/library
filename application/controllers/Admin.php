<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model('M_admin');

		if ($this->session->userdata('logged_in')) {
			redirect('Dashboard');
		}
	}

	public function index()
	{
		if ($this->session->userdata('logged_in') == FALSE) {
			$this->load->view('login');
		} else {
			redirect('Dashboard');
		}

	}
	public function proses_login()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('username', 'username', 'trim|required');
			$this->form_validation->set_rules('password', 'password', 'trim|required');
			if ($this->form_validation->run() == TRUE) {
				if ($this->M_admin->get_login() == TRUE) {
					redirect('dashboard');
				} else {
					$this->session->set_flashdata('message', 'Wrong Username and Password');
					redirect('login');
				}
			} else {
				$this->session->set_flashdata('message', 'Username or Password must be filled!!');
				redirect('login');
			}
		}
	}

	public function register()
	{
		if ($this->session->userdata('login') == FALSE) {
			$this->load->view('register');
		} else {
			redirect('Dashboard');
		}
	}

	public function proses_register()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('username', 'username', 'trim|required');
			$this->form_validation->set_rules('password', 'password', 'trim|required');
			$this->form_validation->set_rules('fullname', 'fullname', 'trim|required');
			if ($this->form_validation->run() == TRUE) {
				if ($this->M_admin->get_register() == TRUE) {
					redirect('login');
				} else {
					$this->session->set_flashdata('message', 'Wrong Username and Password');
					redirect('register');
				}
			} else {
				$this->session->set_flashdata('message', 'Username or Password must be filled!!');
				redirect('register');
			}
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login', 'refresh');
	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */
