<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ReportModel');

		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = [
			'customers' => $this->ReportModel->getCustomers(),
			'categories' => $this->ReportModel->getCategories(),
			'content' => 'Report'
		];
		$this->load->view('template', $data);
	}

	public function salesByDays($days)
	{
		$result = $this->ReportModel->getSalesByDays($days);
		echo json_encode($result);
	}

	public function topBooks()
	{
		$result = $this->ReportModel->getTopSellingBooks();
		echo json_encode($result);
	}

	public function userActivity($userId)
	{
		$result = $this->ReportModel->getUserActivityReport($userId);
		echo json_encode($result);
	}

	public function inventory($categoryId)
	{
		$result = $this->ReportModel->getInventoryReport($categoryId);
		echo json_encode($result);
	}

	public function popularCategories()
	{
		$result = $this->ReportModel->getPopularCategories();
		echo json_encode($result);
	}


}
