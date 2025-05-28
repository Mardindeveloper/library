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

	public function chart_data()
	{
		$query = $this->db->query("
		SELECT DATE(tgl) as date, SUM(total) as total
		FROM transaction
		WHERE tgl >= CURDATE() - INTERVAL 6 DAY
		GROUP BY DATE(tgl)
		ORDER BY DATE(tgl) ASC
	");

		$data = $query->result();

		$labels = [];
		$totals = [];

		// ایجاد آرایه‌ای از ۷ روز گذشته حتی اگر دیتایی نداشته باشن
		for ($i = 6; $i >= 0; $i--) {
			$day = date('Y-m-d', strtotime("-$i days"));
			$labels[$day] = 0; // مقدار پیش‌فرض
		}

		foreach ($data as $row) {
			$labels[$row->date] = $row->total;
		}

		echo json_encode([
			'labels' => array_keys($labels),
			'totals' => array_values($labels)
		]);
	}

	public function best_categories()
	{
		$query = $this->db->query("
SELECT c.category_name, SUM(td.amount) AS total_sold
FROM transaction_detail td
JOIN book b ON td.book_code = b.book_code
JOIN book_category c ON b.category_code = c.category_code
GROUP BY c.category_name
ORDER BY total_sold DESC
LIMIT 5;
	");

		$data = $query->result();

		$labels = [];
		$totals = [];

		foreach ($data as $row) {
			$labels[] = $row->category_name;
			$totals[] = $row->total_sold;
		}

		echo json_encode([
			'labels' => $labels,
			'totals' => $totals
		]);
	}

}

/* End of file Kasir.php */
/* Location: ./application/controllers/Kasir.php */
?>
