<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportModel extends CI_Model
{
	public function getCustomers($id = '')
	{
		return $this->db
			->select('user_id, fullname')
			->where('level', 'customer')
			->get('user')
			->result();
	}
	public function getCategories($id = '')
	{
		return $this->db->get('book_category')->result();
	}

	public function getSalesByDays($days)
	{
		return $this->db
			->select('DATE(transaction_date) as date, SUM(total) as total')
			->from('transaction')
			->where('transaction_date >=', date('Y-m-d', strtotime("-$days days")))
			->where('type_transaction', 'for_sale')
			->group_by('DATE(transaction_date)')
			->order_by('transaction_date', 'ASC')
			->get()
			->result();
	}

	public function getTopSellingBooks($limit = 10)
	{
		$query = $this->db
			->select('book.book_title, COUNT(td.transaction_id) as total_sold')
			->from('transaction_detail td')
			->join('book_copy bc', 'bc.book_id = td.book_id')
			->join('book', 'book.book_id = bc.book_id')
			->group_by('book.book_title')
			->order_by('total_sold', 'DESC')
			->limit($limit)
			->get();

		return $query->result();
	}

	public function getUserActivityReport($userId)
	{
		return $this->db
			->select("user.fullname, COUNT(loan.loan_id) AS total_loans,
				COUNT(CASE WHEN loan.loan_id IS NOT NULL AND loan.return_date IS NOT NULL THEN 1 END) AS returned,
				COUNT(CASE WHEN loan.loan_id IS NOT NULL AND loan.return_date IS NULL THEN 1 END) AS not_returned", false)
			->from('user')
			->join('loan', 'loan.user_id = user.user_id', 'left')
			->where('user.level', 'customer')
			->where('user.user_id', $userId)
			->group_by('user.user_id')
			->get()
			->result();
	}

	public function getInventoryReport($categoryId)
	{
		return $this->db
			->select('book.book_title, book.stock as stock')
			->from('book')
			->join('book_category', 'book_category.category_id = book.category_id')	
			->where('book_category.category_id', $categoryId)
			->group_by('book.book_id')
			->get()
			->result();
	}

	public function getPopularCategories()
	{
		return $this->db
			->select('bc.category_name, COUNT(l.loan_id) as total_loans')
			->from('loan l')
			->join('book_copy bc2', 'bc2.copy_id = l.copy_id')
			->join('book b', 'b.book_id = bc2.book_id')
			->join('book_category bc', 'bc.category_id = b.category_id')
			->group_by('bc.category_id')
			->order_by('total_loans', 'DESC')
			->get()
			->result();
	}


}
