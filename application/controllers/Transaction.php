<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_transaction', 'trans');
		$this->load->model('BookModel', 'book');

		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}
	public function index()
	{
		$data['transaction'] = $this->trans->tm_transaction();
		$data['get_book'] = $this->book->getBooks();
		$data['content'] = "v_transaction";
		$this->load->view('template', $data, FALSE);
	}

	public function addcart($id)
	{
		$cek_stock = $this->trans->cek($id);
		if ($cek_stock == 0) {
			$this->session->set_flashdata('message', 'Out of stock');
			redirect('transaction', 'refresh');
		}
		$detail = $this->book->getBooks($id);
		$data = array(
			'id' => $detail->book_id,
			'qty' => 1,
			'price' => $detail->price,
			'name' => $detail->book_title,
			'options' => array('genre' => $detail->category_name)
		);
		$this->cart->insert($data);
		redirect('transaction');
	}

	public function save()
	{
		if ($this->input->post('update')) {
			for ($i = 0; $i < count($this->input->post('rowid')); $i++) {
				$data = array(
					'rowid' => $this->input->post('rowid')[$i],
					'qty' => $this->input->post('qty')[$i]
				);
				$this->cart->update($data);
			}
			redirect('transaction');
		} elseif ($this->input->post('pay')) {
			$this->form_validation->set_rules('user_id', 'user', 'trim|required');
			$this->form_validation->set_rules('buyer_name', 'buyer_name', 'trim|required');
			if ($this->form_validation->run() == TRUE) {
				$id = $this->trans->save_cart_db();

				if ($id) {
					$data['transaction'] = $this->trans->detail_note($id);
					$this->load->view('print_note', $data, FALSE);
				} else {
					$this->session->set_flashdata('message', 'The purchase operation was not successful. There is insufficient inventory or another error occurred..');
					redirect('transaction');
				}
			} else {
				$this->session->set_flashdata('message', 'Name of Cashier, Customer must be filled!!!');
				redirect('transaction');
			}
		}
	}

	public function delete_cart($id)
	{
		$data = array(
			'rowid' => $id,
			'qty' => 0
		);
		$this->cart->update($data);
		redirect('transaction');
	}
	public function clearcart()
	{
		$this->cart->destroy();
		redirect('transaction');
	}

	public function view_note($transaction_code = null)
	{
		if (!$transaction_code) {
			show_404();
			return;
		}

		$data['transaction'] = $this->trans->get_transaction($transaction_code);
		if (!$data['transaction']) {
			show_404();
			return;
		}

		$this->load->view('print_note', $data);
	}

	public function filter()
	{
		$type = $this->input->post('type');
		$books = $this->trans->getBooksByType($type);

		echo json_encode($books);
	}
}

/* End of file transaction.php */
/* Location: ./application/controllers/transaction.php */
