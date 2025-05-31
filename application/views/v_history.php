<header class="page-header">
	<div class="container-fluid">
		<h2 class="no-margin-bottom">Transaction History</h2>
	</div>
</header>

<div class="container-fluid py-3">
	<div class="row">
		<div class="col-md-12">
			<div class="card rouded-0 shadow">
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
								<td>Customer's Name</td>
								<td>Date</td>
								<td>Book</td>
								<td>Qty</td>
								<td>Amount</td>
								<td>Cashier</td>
								<td>Action</td>
							</tr>
						</thead>
						<tbody style="background-color: white;">
							<?php $no = 0;
							foreach ($get_history as $history):
								$no++; ?>

								<tr>
									<td><?= $no ?></td>
									<td><?= $history->buyer_name ?></td>
									<td><?= $history->transaction_date ?></td>
									<td><?= $history->book_title ?></td>
									<td><?= $history->quantity ?></td>
									<td>$<?= number_format($history->total) ?></td>
									<td><?= $history->fullname ?></td>
									<td class="text-center">
										<a href="<?= base_url('transaction/view_note/' . $history->transaction_id); ?>"
											target="_blank" class="btn btn-success btn-sm rounded-1"><i
												class="fa fa-print"></i></a>
									</td>

								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$('#example').DataTable();
	});
</script>
