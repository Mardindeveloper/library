<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Book extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('BookModel');

		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}
	public function index()
	{
		$data = [
			'get_book' => $this->BookModel->getBooks(),
			'category' => $this->BookModel->getCategory(),
			'authors' => $this->BookModel->getAuthor(),
			'content' => 'listBook',
		];

		$this->load->view('template', $data);
	}

	// add and update Book
	public function saveBook()
	{
		if ($this->input->method() !== 'post') {
			redirect('book', 'refresh');
		}

		$this->form_validation->set_rules([
			['field' => 'book_title', 'label' => 'book_title', 'rules' => 'trim|required'],
			['field' => 'year', 'label' => 'year', 'rules' => 'trim|required'],
			['field' => 'price', 'label' => 'price', 'rules' => 'trim|required'],
			['field' => 'category_id', 'label' => 'category_id', 'rules' => 'trim|required'],
			['field' => 'publisher', 'label' => 'publisher', 'rules' => 'trim|required'],
			['field' => 'stock', 'label' => 'stock', 'rules' => 'trim|required'],
		]);

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata([
				'message' => validation_errors(),
				'messageType' => 'danger'
			]);
			redirect('book', 'refresh');
		}

		$data = $this->input->post();
		$bookId = $data['book_id'] ?? '';
		$fileName = '';
		if ($_FILES['book_img']['name'] != "") {
			$config['upload_path'] = './assets/picProduct/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 2048;
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('book_img')) {
				$this->session->set_flashdata([
					'message' => $this->upload->display_errors(),
					'messageType' => 'danger'
				]);
				redirect('book', 'refresh');
			}

			$fileName = $this->upload->data('file_name');
		}

		if ($this->BookModel->saveBook($data, $fileName, $bookId)) {
			$this->session->set_flashdata([
				'message' => 'Book has been added successfully',
				'messageType' => 'success'
			]);
		} else {
			$this->session->set_flashdata([
				'message' => 'Book has failed to Add',
				'messageType' => 'danger'
			]);
		}
		redirect('book', 'refresh');
	}

	public function getBookById($id)
	{
		$data = $this->BookModel->getBooks($id);
		echo json_encode($data);
	}

	public function deleteBook($bookId = '')
	{
		if (!$this->BookModel->deleteBook($bookId)) {
			$this->session->set_flashdata([
				'message' => 'Delete Failed',
				'messageType' => 'danger'
			]);
			redirect('book', 'refresh');
		}

		$this->session->set_flashdata([
			'message' => 'Book has been deleted successfully',
			'messageType' => 'success'
		]);
		redirect('book', 'refresh');
	}

}

/* End of file book.php */
/* Location: ./application/controllers/book.php */
