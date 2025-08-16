<?php
defined('BASEPATH') or exit('No direct script access allowed');

class history extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model('HistoryModel');

		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}

	public function index()
	{
		$data['get_history'] = $this->HistoryModel->get_history();
		$data['content'] = "v_history";
		$this->load->view('template', $data, FALSE);
	}

}
