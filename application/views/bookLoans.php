<header class="page-header">
	<div class="container-fluid">
		<h2 class="no-margin-bottom">Loaned Books</h2>
	</div>
</header>
<br>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card rouded-0 shadow">
				<div class="card-header">
					<div class="card-title mb-0">List of Books</div>
				</div>
				<div class="card-body">
					<table class="table table-hover table-bordered" id="example" style="background-color: #eef9f0;">
						<thead style="background-color: #464b58; color:white;">
							<tr>
								<th>#</th>
								<th>Book Title</th>
								<td>Category</td>
								<th>Loan date</th>
								<th>Due date</th>
								<th>Return date</th>
								<th>Status</th>
								<th>Act.</th>
							</tr>

						</thead>
						<tbody style="background-color: white;">
							<?php $no = 0;
							$statusBookLoan = ['returned' => 'Returned', 'on_loan' => 'ON loan', 'overdue' => 'Overdue'];
							foreach ($bookLoan as $book):
								$no++; ?>
								<tr>

									<td><?= $no ?></td>
									<td><?= $book->book_title ?></td>
									<td><?= $book->category_name ?></td>
									<td><?= $book->loan_date ?></td>
									<td><?= $book->due_date ?></td>
									<td><?= $book->return_date ?? 'Not yet returned.' ?></td>
									<td><?= $statusBookLoan[$book->status] ?></td>
									<td class="text-center">
										<?php if ($book->status === 'on_loan'): ?>
											<a href="#saveLoan" onclick="edit('<?= $book->loan_id ?>')"
												class="btn btn-primary btn-sm rounded-1" data-toggle="modal"><i
													class="fa fa-exchange"></i></a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="saveLoan">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="title-modal">Edit Loan</span>
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
				</div>
				<form action="<?= base_url('book/saveLoans') ?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="loan_id" id="loan_id">
					<input type="hidden" name="user_id" id="user_id">
					<div class="modal-body">
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Book Title</label></div>
							<div class="col-sm-7">
								<span id="bookTitle"></span>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Category</label></div>
							<div class="col-sm-7">
								<span id="category"></span>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Loan date</label></div>
							<div class="col-sm-7">
								<span id="loan_date"></span>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Loan date</label></div>
							<div class="col-sm-7">
								<span id="due_date"></span>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Return date</label></div>
							<div class="col-sm-7">
								<input type="date" id="return_date" name="return_date" class="form-control">
							</div>
						</div>
					</div>
					<div class="modal-footer justify-content-end">
						<input type="submit" value="Save" class="btn btn-primary btn-sm rounded-1">
						<button type="button" class="btn btn-default btn-sm border rounded-1"
							data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$('#example').DataTable();
	});

	function edit(id) {
		$('#saveBook').on('show.bs.modal', function () {
			this.querySelector('form').reset();
		});

		document.querySelector('.title-modal').innerText = 'Edit Book';
		$.ajax({
			type: "post",
			url: "<?= base_url() ?>book/getLoanById/" + id,
			dataType: "json",
			success: function (data) {
				$("#loan_id").val(data.loan_id);
				$("#return_date").val(data.return_date);
				$("#user_id").val(data.user_id);
				$("#bookTitle").text(data.book_title);
				$("#category").text(data.category_name);
				$("#loan_date").text(data.loan_date);
				$("#due_date").text(data.due_date);
			}
		});
	}
</script>
