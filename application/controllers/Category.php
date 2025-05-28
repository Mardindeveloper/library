<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('CategoryModel');

		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}
	public function index()
	{
		$data = [
			'content' => "category",
			'allCategory' => $this->CategoryModel->get_category(),
		];
		$this->load->view('template', $data);
	}

	public function addCategory()
	{
		if ($this->input->method() === 'post') {
			$data = $this->input->post();
			if (!$this->CategoryModel->save_category($data)) {
				$this->session->set_flashdata('message', 'Category Details has faile to add!');
			} else {
				$this->session->set_flashdata('message', 'Category Details has been added successfully');
			}
			redirect('category', 'refresh');
		}
	}
	public function getCategoryById($id)
	{
		$data = $this->CategoryModel->get_category($id);
		echo json_encode($data);
	}
	public function updateCategory()
	{
		if ($this->input->method() === 'post') {
			$data = $this->input->post();
			if ($this->CategoryModel->save_category($data)) {
				$this->session->set_flashdata([
					'message' => 'Category update successfully!',
					'messageType' => 'success'
				]);
			} else {
				$this->session->set_flashdata([
					'message' => 'Category update failed!',
					'messageType' => 'danger'
				]);
			}

			redirect('category', 'refresh');
		}
	}

	public function deleteCategory($id = '')
	{
		if ($this->CategoryModel->deleteCategory($id)) {
			$this->session->set_flashdata([
				'message' => 'Category deleted successfully!',
				'messageType' => 'success'
			]);
		} else {
			$this->session->set_flashdata([
				'message' => 'Category deleted Failed!',
				'messageType' => 'danger'
			]);
		}
		redirect('category', 'refresh');
	}

}

/* End of file category.php */
/* Location: ./application/controllers/category.php */
