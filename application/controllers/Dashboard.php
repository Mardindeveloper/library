<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Dashboard');

		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}

	public function index()
	{
		if ($this->session->userdata('logged_in') == TRUE) {

			$data['content'] = 'Dashboard';
			$data['jml_book'] = $this->M_Dashboard->get_jml_book();
			$data['jml_transaction'] = $this->M_Dashboard->get_jml_transaction();
			$data['jml_pengguna'] = $this->M_Dashboard->get_jml_pengguna();
			$data['book_cat'] = $this->M_Dashboard->get_book_cat();
			$data['sys_user'] = $this->M_Dashboard->get_sys_user();
			$data['book_stock'] = $this->M_Dashboard->get_book_stock();
			$data['sales_p'] = $this->M_Dashboard->get_sales_p();
			$this->load->view('template', $data);

		} else {
			redirect('admin/login');
		}
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
