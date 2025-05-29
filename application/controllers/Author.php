<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Author extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('AuthorModel');

		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = [
			'content' => 'author',
			'authors' => $this->AuthorModel->getAuthor(),
		];
		$this->load->view('template', $data);
	}

	// add and update Author
	public function saveAuthor()
	{
		if ($this->input->method() !== 'post') {
			redirect('author', 'refresh');
		}

		$this->form_validation->set_rules([
			['field' => 'name', 'label' => 'name', 'rules' => 'trim|required'],
		]);

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata([
				'message' => validation_errors(),
				'messageType' => 'danger'
			]);
			redirect('author', 'refresh');
		}

		$data = $this->input->post();
		$authorId = $data['author_id'] ?? '';
		if ($this->AuthorModel->saveAuthor($data, $authorId)) {
			$this->session->set_flashdata([
				'message' => 'Author has been added successfully',
				'messageType' => 'success'
			]);
		} else {
			$this->session->set_flashdata([
				'message' => validation_errors(),
				'messageType' => 'danger'
			]);
		}
		redirect('author', 'refresh');
	}

	public function getAuthorById($id) {
		$data = $this->AuthorModel->getAuthor($id);
		echo json_encode($data);
	}
}
