<header class="page-header">
	<div class="container-fluid">
		<h2 class="no-margin-bottom">Sales Transaction</h2>
	</div>
</header>
<br>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-7">
			<div class="card rouded-0 shadow">
				<div class="card-header">
					<div class="card-title mb-0">List of Books</div>
				</div>
				<div class="card-body">
					<select id="filterType" class="form-control w-25 mb-3">
						<option value="for_sale">For Sale</option>
						<option value="available_for_loan">Available For Loan</option>
					</select>
					<table class="table table-hover table-bordered" id="bookTable" style="background-color: #eef9f0;">
						<thead style="background-color: #464b58; color:white;">
							<tr>
								<th>#</th>
								<th>Book Title</th>
								<th>Author Name</th>
								<td>Category</td>
								<th>Price</th>
								<th>Stock</th>
								<th>Act.</th>
							</tr>
						</thead>
						<tbody style="background-color: white;">

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<div class="card rounded-1 shadow">
				<div class="card-header">
					<div class="card-title mb-0">Cart List</div>
				</div>
				<div class="card-body">
					<form action="<?= base_url('/transaction/save') ?>" method="post" id="product-options">
						<input type="hidden" name="type_transaction" id="type_transaction" value="for_sale">
						<?php $cart_empty = count($this->cart->contents()) === 0;
						$levelUser = $this->session->userdata('level'); ?>
						<div class="mb-3">
							Cashier : <?= $levelUser; ?>
							<input type="hidden" name="user_id" value="<?= $this->session->userdata('user_code'); ?>">
						</div>
						<div class="mb-3">
							<select type="text" name="customer_id" required class="form-control">
								<?php foreach ($customers as $customer): ?>
									<option value="<?= $customer->user_id; ?>"><?= $customer->fullname; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<table class="table table-hover" id="example" style="background-color: white;">
							<thead style="background-color:#636363; color:white;">
								<tr>
									<td>#</td>
									<td>Title</td>
									<td>Qty</td>
									<td>Price</td>
									<td>Subtotal</td>
									<td>Action</td>
								</tr>
							</thead>
							<?php $no = 0;
							foreach ($this->cart->contents() as $items):
								$no++; ?>
								<input type="hidden" name="book_code[]" value="<?= $items['id'] ?>">
								<input type="hidden" name="rowid[]" value="<?= $items['rowid'] ?>">
								<tr>
									<td><?= $no ?></td>
									<td><?= $items['name'] ?></td>
									<td width="1"><input  type="text" name="qty[]" value="<?= $items['qty'] ?>"
											class="form-control qty-input" style="padding:4px;"></td>
									<td class="text-right">$<?= number_format($items['price']) ?></td>
									<td class="text-right">$<?= number_format($items['subtotal']) ?></td>
									<td><a href="<?= base_url('/transaction/delete_cart/' . $items['rowid']) ?>"
											class="btn btn-danger btn-sm"><span class="fa fa-trash"
												aria-hidden="true"></span></a></td>
								</tr>
								<input type="hidden" name="bookname" value="<?= $items['name'] ?>">
								<input type="hidden" name="book_qty" value="<?= $items['qty'] ?>">
							<?php endforeach ?>
							<input type="hidden" name="total" value="<?= $this->cart->total() ?>">

							<th colspan="4">Total Amount</th>
							<th class="text-right">$<?= number_format($this->cart->total()) ?></th>
							<th></th>

							</tr>
						</table>
						<div class="text-center">
							<input type="submit" name="update" value="Update Quantity"
								class="btn btn-primary rounded-1 btn-sm">
							<input type="submit" name="pay" onclick="return confirm('Are you sure?')"
								class="btn btn-success rounded-1 btn-sm" value="Pay" <?= $cart_empty ? 'disabled' : '' ?>>
							<a class="btn btn-danger rounded-1 btn-sm <?= $cart_empty ? ' disabled' : '' ?>"
								href="<?= base_url('/transaction/clearcart') ?>">Clear Cart</a>
						</div>
					</form>
					<?php if ($this->session->flashdata('message')): 
						$messageType = $this->session->flashdata('message_type') ?? 'warning';
						?>
						<div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
							<?= $this->session->flashdata('message'); ?>
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		let siteUrl = '<?= base_url(); ?>';
		let dataTable = $('#bookTable').DataTable();

		let currentType = localStorage.getItem('selectedFilterType') || 'for_sale';
		$('#filterType').val(currentType);

		function loadBooks(type) {
			$.ajax({
				url: '<?= base_url('transaction/filter') ?>',
				type: 'POST',
				data: { type: type },
				dataType: 'json',
				success: function (data) {
					dataTable.clear().draw();

					let id = 0;
					data.forEach(book => {
						id++;
						dataTable.row.add([
							id,
							book.book_title,
							book.author_name,
							book.category_name,
							book.price,
							book.stock,
							`<a href="${siteUrl + 'transaction/addcart/' + book.book_id}">
							<button class="btn btn-outline-primary rounded-1 btn-sm" id="addcart">
								<span class="fa fa-shopping-cart" aria-hidden="true"></span>
							</button>
						</a>`
						]).draw(false);
					});
				}
			});
		}

		function checkCartStatus() {
			$.ajax({
				url: '<?= base_url('transaction/cart_status') ?>',
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					if (!response.cart_empty) {
						$('#filterType').prop('disabled', true);
						$('.qty-input').prop('disabled', true);
						$('#type_transaction').val(currentType);
					} else {
						$('#filterType').prop('disabled', false);
					}
				}
			});
		}

		loadBooks(currentType);
		checkCartStatus();

		$('#filterType').on('change', function () {
			if (!$(this).prop('disabled')) {
				const selectedType = $(this).val();
				localStorage.setItem('selectedFilterType', selectedType); 
				loadBooks(selectedType);
			}
		});
	});
</script>
