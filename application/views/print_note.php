<h2 style="text-align:center; margin-bottom: 20px;">Transaction Note</h2>
<p>
	<strong>Transaction No. :</strong> <?= $transaction->transaction_id ?><br>
	<strong>Cashier :</strong> <?= $transaction->fullname ?><br>
	<strong>Customer :</strong> <?= $transaction->buyer_name ?><br>
	<strong>Date :</strong> <?= $transaction->transaction_date  ?><br>
</p>

<div id="control-buttons"
	style="margin: 20px auto; display: flex; justify-content: space-between; align-items: center;">
	<a href="<?= base_url('history') ?>">
		<button style="padding: 8px 16px; background-color: #ccc; border: none; border-radius: 5px; cursor: pointer;">
			بازگشت به تراکنش‌ها
		</button>
	</a>

	<button onclick="printInvoice()"
		style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
		چاپ فاکتور
	</button>
</div>


<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
	<thead>
		<tr style="background-color: #007bff; color: white;">
			<th style="padding: 10px; border: 1px solid #ddd;">No</th>
			<th style="padding: 10px; border: 1px solid #ddd;">Book Title</th>
			<th style="padding: 10px; border: 1px solid #ddd; text-align:right;">Price</th>
			<th style="padding: 10px; border: 1px solid #ddd; text-align:center;">Amount</th>
			<th style="padding: 10px; border: 1px solid #ddd; text-align:right;">Subtotal</th>
		</tr>
	</thead>
	<tbody>
		<?php $no = 0;
		foreach ($this->trans->detail_transaction($transaction->transaction_id) as $book):
			$no++; ?>
			<tr style="background-color: <?= $no % 2 == 0 ? '#f9f9f9' : 'white' ?>">
				<td style="padding: 8px; border: 1px solid #ddd; text-align:center;"><?= $no ?></td>
				<td style="padding: 8px; border: 1px solid #ddd;"><?= $book->book_title ?></td>
				<td style="padding: 8px; border: 1px solid #ddd; text-align:right;"><?= number_format($book->price) ?></td>
				<td style="padding: 8px; border: 1px solid #ddd; text-align:center;"><?= $book->quantity ?></td>
				<td style="padding: 8px; border: 1px solid #ddd; text-align:right;">
					<?= number_format(($book->price * $book->quantity)) ?>
				</td>
			</tr>
		<?php endforeach ?>
		<tr style="background-color: #007bff; color: white; font-weight: bold;">
			<td colspan="4" style="padding: 10px; border: 1px solid #ddd; text-align:right;">Total</td>
			<td style="padding: 10px; border: 1px solid #ddd; text-align:right;">
				<?= number_format($transaction->total) ?>
			</td>
		</tr>
	</tbody>
</table>

<script>
	if (window.location.href.includes('transaction/save')) {
		document.getElementById('control-buttons').style.display = 'none';
		window.print();
		setTimeout(() => {
			location.href = "<?= base_url('/transaction/clearcart') ?>";
		}, 2500);
	}
	function printInvoice() {
		// مخفی کردن دکمه‌ها هنگام چاپ
		document.getElementById('control-buttons').style.display = 'none';
		window.print();
		// بعد از چاپ دکمه‌ها دوباره نمایش داده شوند
		document.getElementById('control-buttons').style.display = 'block';
	}
</script>
