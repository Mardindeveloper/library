<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('DashboardModel');

		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = [
			'content' => 'Dashboard',
			'allBooks' => $this->DashboardModel->countTableRows('book'),
			'totalTransactionAmount' => $this->DashboardModel->sumTableColumn('transaction', 'total'),
			'countTransactions' => $this->DashboardModel->countTransactionsByUserLevel(),
			'bookCategory' => $this->DashboardModel->countTableRows('book_category'),
			'countUserLogin' => $this->DashboardModel->countTableRows('user'),
			'bookStock' => $this->DashboardModel->sumTableColumn('book', 'stock'),
			'last24Hours' => $this->DashboardModel->sumTableColumn('transaction', 'total', ['transaction_date' => date('Y-m-d')]),
		];
		$this->load->view('template', $data);
	}

}
