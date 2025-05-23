<header class="page-header">
	<div class="container-fluid">
		<h2 class="panel-title">Dashboard</h2>
	</div>
</header>
<div class="main-content">
	<?php
	if ($this->session->userdata('level') == 'admin') { ?>
		<section class="dashboard-counts no-padding-bottom">
			<div class="container-fluid">
				<div class="row py-1">
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-violet"><i class="fa fa-book"></i></div>
									<a href="<?php echo base_url('/book') ?>" class="text-secondary">
										<div class="title"><span>Total Book</span></div>
									</a>
									<span class="number"><?php echo $jml_book->jml_book; ?></span>
								</div>
							</div>
						</div>
					</div>
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-green"><i class="fa fa-dollar"></i></div>
									<a href="<?php echo base_url('/history') ?>" class="text-secondary">
										<div class="title"><span>Earnings</span></div>
									</a>
									<span class="number"><?php echo '$' . $jml_transaction->jml_transaction; ?></span>
								</div>
							</div>
						</div>
					</div>
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-red"><i class="fa fa-exchange"></i></div>
									<a href="<?php echo base_url('/history') ?>" class="text-secondary">
										<div class="title"><span>Total Transaction</span></div>
									</a>
									<span class="number"><?php echo $jml_pengguna->jml_pengguna; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-1">
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-warning"><i class="fa fa-bookmark" style="color: white;"></i></div>
									<a href="<?php echo base_url('/category') ?>" class="text-secondary">
										<div class="title"><span>Categories</span></div>
									</a>
									<span class="number"><?php echo $book_cat->book_cat; ?></span>
								</div>
							</div>
						</div>
					</div>
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-gray"><i class="fa fa-user-secret"></i></div>
									<a href="<?php echo base_url('/user') ?>" class="text-secondary">
										<div class="title"><span>System Users</span></div>
									</a>
									<span class="number"><?php echo $sys_user->sys_user; ?></span>
								</div>
							</div>
						</div>
					</div>
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-info"><i class="fa fa-th-large" style="color: white;"></i></div>
									<a href="<?php echo base_url('/book') ?>" class="text-secondary">
										<div class="title"><span>Stocks</span></div>
									</a>
									<span class="number"><?php echo $book_stock->book_stock; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-1">

					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div class="icon bg-green"><i class="fa fa-hourglass"></i></div>
									<a href="<?php echo base_url('/history') ?>" class="text-secondary">
										<div class="title"><span>Sales (24hrs)</span></div>
									</a>
									<span class="number"><?php echo '$' . $sales_p->sales_p; ?></span>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="row py-1">
					<div class="col-xl-8 col-sm-4">
						<canvas id="salesChart" width="400" height="200"></canvas>
					</div>
					<div class="col-xl-4 col-sm-4 d-flex justify-content-center align-items-center flex-column">
						<h3 class="text-center">Best Categories</h3>
						<canvas id="topCategoriesChart" width="400" height="200"></canvas>
					</div>
				</div>
			</div>
		</section>
	<?php } elseif (($this->session->userdata('level') == 'cashier')) { ?>
		<section class="dashboard-counts no-padding-bottom">
			<div class="container-fluid">
				<div class="row py-1">
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-violet"><i class="fa fa-book"></i></div>
									<a href="#" class="text-secondary">
										<div class="title"><span>Total Book</span></div>
									</a>
									<span class="number"><?php echo $jml_book->jml_book; ?></span>
								</div>
							</div>
						</div>
					</div>
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-warning"><i class="fa fa-bookmark" style="color: white;"></i></div>
									<a href="#" class="text-secondary">
										<div class="title"><span>Categories</span></div>
									</a>
									<span class="number"><?php echo $book_cat->book_cat; ?></span>
								</div>
							</div>
						</div>
					</div>
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-red"><i class="fa fa-exchange"></i></div>
									<a href="#" class="text-secondary">
										<div class="title"><span>Total Transaction</span></div>
									</a>
									<span class="number"><?php echo $jml_pengguna->jml_pengguna; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-1">
					<!-- Item -->
					<div class="col-xl-4 col-sm-4">
						<div class="card rounded-1 shadow">
							<div class="card-body">
								<div class="item d-flex align-items-center">
									<div class="icon bg-info"><i class="fa fa-th-large" style="color: white;"></i></div>
									<a href="#" class="text-secondary">
										<div class="title"><span>Books Stock</span></div>
									</a>
									<span class="number"><?php echo $book_stock->book_stock; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	fetch('<?= base_url('dashboard/chart_data') ?>')
		.then(response => response.json())
		.then(data => {
			const ctx = document.getElementById('salesChart').getContext('2d');
			const chart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: data.labels,
					datasets: [{
						label: 'Sales in Last 7 Days',
						data: data.totals,
						borderColor: 'rgba(75, 192, 192, 1)',
						backgroundColor: 'rgba(75, 192, 192, 0.2)',
						fill: true
					}]
				},
				options: {
					responsive: true,
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		});

	fetch('<?= base_url('dashboard/best_categories') ?>')
		.then(res => res.json())
		.then(data => {
			const canvas = document.getElementById('topCategoriesChart');
			canvas.width = 400;  // عرض به پیکسل
			canvas.height = 400; // ارتفاع به پیکسل

			const ctx = canvas.getContext('2d');
			new Chart(ctx, {
				type: 'pie',
				data: {
					labels: data.labels,
					datasets: [{
						data: data.totals,
						backgroundColor: [
							'#FF6384',
							'#36A2EB',
							'#FFCE56',
							'#4BC0C0',
							'#9966FF'
						]
					}]
				},
				options: {
					responsive: false, // غیرفعال کردن ریسپانسیو
					plugins: {
						legend: {
							position: 'top'
						}
					}
				}
			});
		});

</script>
