<header class="page-header">
	<div class="container-fluid">
		<h2 class="no-margin-bottom">Customer Users</h2>
	</div>
</header>

<div class="table-agile-info">
	<div class="panel panel-default">

		<div class="container-fluid my-3">
			<div class="card rounded-1 shadow">
				<?php if ($this->session->flashdata('message') != null) { ?>
					<div class="card-header">
						<?= "<div class='alert alert-success alert-dismissible fade show' role='alert'>"
							. $this->session->flashdata('message') . "<button type='button' class='close'
								data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button> </div>" ?>
					</div>
				<?php } ?>
				<div class="card-body">
					<table class="table table-hover table-bordered" id="example" ui-options=ui-options="{
					&quot;paging&quot;: {
					  &quot;enabled&quot;: true
					},
					&quot;filtering&quot;: {
					  &quot;enabled&quot;: true
					},
					&quot;sorting&quot;: {
					  &quot;enabled&quot;: true
					}}">
						<thead style="background-color: #464b58; color:white;">
							<tr>
								<td>#</td>
								<td>Full Name</td>
								<td>Username</td>
								<td>Eligible to Borrow</td>
								<td>Action</td>
							</tr>
						</thead>
						<tbody style="background-color: white;">
							<?php $no = 0;
							foreach ($get_user as $user):
								$no++; ?>

								<tr>
									<td><?= $no ?></td>
									<td><?= $user->fullname ?></td>
									<td><?= $user->username ?></td>
									<td><?= $user->can_loan ? 'Yes' : 'No' ?></td>
									<td>
										<a href="#showLoan" onclick="showLoanCustomer('<?= $user->user_id ?>')"
											class="btn btn-primary btn-sm" data-toggle="modal">
											<i class="fa fa-eye"></i>
										</a>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="showLoan">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					Loan & Return History
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-hover table-bordered" style="width: 100%;" id="loanTable"
						ui-options=ui-options="{
					&quot;paging&quot;: {
					  &quot;enabled&quot;: true
					},
					&quot;filtering&quot;: {
					  &quot;enabled&quot;: true
					},
					&quot;sorting&quot;: {
					  &quot;enabled&quot;: true
					}}">
						<thead style="background-color: #464b58; color:white;">
							<tr>
								<td>#</td>
								<td>Book Title</td>
								<td>Loan Date</td>
								<td>Due Date</td>
								<td>Return Date</td>
								<td>Status</td>
							</tr>
						</thead>
						<tbody style="background-color: white;">

						</tbody>
					</table>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	let dataTable;

	$(document).ready(function () {
		dataTable = $('#loanTable').DataTable();

		$('#showLoan').on('shown.bs.modal', function () {
			dataTable.columns.adjust().draw();
		});
	});

	function showLoanCustomer(customerId) {
		$.ajax({
			url: '<?= base_url('user/getLoanById/') ?>' + customerId,
			type: 'POST',
			dataType: 'json',
			success: function (data) {
				dataTable.clear();

				let id = 0;
				data.forEach(loan => {
					id++;
					dataTable.row.add([
						id,
						loan.book_title,
						loan.loan_date,
						loan.due_date,
						loan.return_date ?? '-',
						loan.status,
					]);
				});

				dataTable.draw();
			}
		});
	}
</script>
