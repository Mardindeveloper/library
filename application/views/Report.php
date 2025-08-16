<header class="page-header">
	<div class="container-fluid">
		<h2 class="no-margin-bottom">Loaned Books</h2>
	</div>
</header>
<br>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div id="accordion">
				<!-- Accordion Item 1 (Always Open) -->
				<div class="card">
					<div class="card-header" id="headingOne">
						<h5 class="mb-0">
							<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
								aria-expanded="true">
								Sales By Days
							</button>
						</h5>
					</div>

					<div id="collapseOne" class="collapse" data-parent="#accordion">
						<div class="card-body">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="reportRange">Sales report:</label>
										<select class="form-control" id="reportRange">
											<option value="7">Last 7 days</option>
											<option value="15">Last 15 days</option>
											<option value="30">Last month</option>
										</select>
									</div>
								</div>
							</div>
							<canvas id="salesChart" width="100%" height="40"></canvas>
						</div>
					</div>
				</div>

				<!-- Accordion Item 2 -->
				<div class="card">
					<div class="card-header" id="headingTwo">
						<h5 class="mb-0">
							<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo">
								Top Books Chart
							</button>
						</h5>
					</div>

					<div id="collapseTwo" class="collapse" data-parent="#accordion">
						<div class="card-body">
							<canvas id="topBooksChart" style="max-width: 100%; height: 200px;"></canvas>
						</div>
					</div>
				</div>

				<!-- Accordion Item 3 -->
				<div class="card">
					<div class="card-header" id="headingThree">
						<h5 class="mb-0">
							<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree">
								User Activity Chart
							</button>
						</h5>
					</div>

					<div id="collapseThree" class="collapse" data-parent="#accordion">
						<div class="card-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="customer">Sales report:</label>
										<select class="form-control" id="customer">
											<option value="not-select" selected>Not select</option>
											<?php foreach ($customers as $customer): ?>
												<option value="<?= $customer->user_id ?>"><?= $customer->fullname ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<canvas id="userActivityChart" style="max-width: 100%; height: 200px;"></canvas>
						</div>
					</div>
				</div>

				<!-- Accordion Item 4 -->
				<div class="card">
					<div class="card-header" id="headingFour">
						<h5 class="mb-0">
							<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour">
								Inventory Chart
							</button>
						</h5>
					</div>

					<div id="collapseFour" class="collapse" data-parent="#accordion">
						<div class="card-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="category">Categories report:</label>
										<select class="form-control" id="category">
											<option value="not-select" selected>Not select</option>
											<?php foreach ($categories as $category): ?>
												<option value="<?= $category->category_id ?>">
													<?= $category->category_name ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<canvas id="inventoryChart" style="max-width: 100%; height: 200px;"></canvas>
						</div>
					</div>
				</div>

				<!-- Accordion Item 5 -->
				<div class="card">
					<div class="card-header" id="headingFive">
						<h5 class="mb-0">
							<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive">
								Popular Categories Chart
							</button>
						</h5>
					</div>

					<div id="collapseFive" class="collapse" data-parent="#accordion">
						<div class="card-body">
							<canvas id="popularCategoriesChart" style="max-width: 100%; height: 200px;"></canvas>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>


<script>
	let chart;

	$(document).ready(function () {
		$('#reportRange').on('change', function () {
			fetchChartData($(this).val());
		});

		$('#customer').on('change', function () {
			if ($(this).val() !== 'not-select') {
				fetchAndRenderUserActivity($(this).val());
			} else {
				if (window.userActivityChart instanceof Chart) {
					window.userActivityChart.destroy();
				}
			}
		});

		$('#category').on('change', function () {
			if ($(this).val() !== 'not-select') {
				fetchAndRenderInventory($(this).val());
			} else {
				if (window.inventoryChart instanceof Chart) {
					window.inventoryChart.destroy();
				}
			}
		});

		let topBooksChart = null;

		function fetchChartData(days) {
			$.ajax({
				url: '<?= base_url("report/salesByDays/") ?>' + days,
				method: 'GET',
				dataType: 'json',
				success: function (data) {
					const labels = data.map(row => row.date);
					const totals = data.map(row => row.total);

					if (chart) {
						chart.destroy();
					}

					const ctx = document.getElementById('salesChart').getContext('2d');
					chart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: labels,
							datasets: [{
								label: 'Total sales (dollars)',
								data: totals,
								backgroundColor: 'rgba(54, 162, 235, 0.6)',
								borderColor: 'rgba(54, 162, 235, 1)',
								borderWidth: 1
							}]
						},
						options: {
							responsive: true,
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true
									}
								}]
							}
						}
					});
				}
			});
		}

		function fetchTopBooks() {
			$.ajax({
				url: '<?= base_url("report/topBooks") ?>',
				method: 'GET',
				dataType: 'json',
				success: function (data) {
					const labels = data.map(item => item.book_title);
					const sales = data.map(item => parseInt(item.total_sold));

					const ctx = document.getElementById('topBooksChart').getContext('2d');

					if (topBooksChart instanceof Chart) {
						topBooksChart.destroy();
					}

					topBooksChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: labels,
							datasets: [{
								label: 'تعداد فروش',
								data: sales,
								backgroundColor: 'rgba(75, 192, 192, 0.5)',
								borderColor: 'rgba(75, 192, 192, 1)',
								borderWidth: 1
							}]
						},
						options: {
							responsive: true,
							scales: {
								xAxes: [{
									ticks: {
										beginAtZero: true
									}
								}]
							}
						}
					});
				}
			});
		}

		function fetchAndRenderUserActivity(userId) {
			$.ajax({
				url: '<?= base_url("report/userActivity/") ?>' + userId,
				method: 'GET',
				dataType: 'json',
				success: function (data) {
					const labels = data.map(item => item.fullname);
					const totalLoans = data.map(item => +item.total_loans);
					const returned = data.map(item => +item.returned);
					const notReturned = data.map(item => +item.not_returned);

					const ctx = document.getElementById('userActivityChart').getContext('2d');

					if (window.userActivityChart instanceof Chart) {
						window.userActivityChart.destroy();
					}

					window.userActivityChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: labels,
							datasets: [
								{
									label: 'Total trusts',
									data: totalLoans,
									backgroundColor: 'rgba(54, 162, 235, 0.6)',
								},
								{
									label: 'Returned books',
									data: returned,
									backgroundColor: 'rgba(75, 192, 192, 0.6)',
								},
								{
									label: 'Books of No Return',
									data: notReturned,
									backgroundColor: 'rgba(255, 99, 132, 0.6)',
								}
							]
						},
						options: {
							responsive: true,
							scales: {
								y: { beginAtZero: true }
							}
						}
					});
				}
			});
		}

		function fetchAndRenderInventory(categoryId) {
			$.ajax({
				url: '<?= base_url("report/inventory/") ?>' + categoryId,
				method: 'GET',
				dataType: 'json',
				success: function (data) {
					const labels = data.map(item => item.book_title);
					const stock = data.map(item => +item.stock);

					const ctx = document.getElementById('inventoryChart').getContext('2d');

					if (window.inventoryChart instanceof Chart) {
						window.inventoryChart.destroy();
					}

					window.inventoryChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: labels,
							datasets: [{
								label: 'Number of copies available',
								data: stock,
								backgroundColor: 'rgba(255, 206, 86, 0.6)'
							}]
						},
						options: {
							indexAxis: 'y', // افقی شدن نمودار در نسخه 3 و 4
							responsive: true,
							scales: {
								x: { beginAtZero: true }
							}
						}
					});
				}
			});
		}

		function fetchAndRenderPopularCategories() {
			$.ajax({
				url: '<?= base_url("report/popularCategories/") ?>',
				method: 'GET',
				dataType: 'json',
				success: function (data) {
					const labels = data.map(item => item.category_name);
					const totalLoans = data.map(item => +item.total_loans);

					const ctx = document.getElementById('popularCategoriesChart').getContext('2d');

					if (window.popularCategoriesChart instanceof Chart) {
						window.popularCategoriesChart.destroy();
					}

					window.popularCategoriesChart = new Chart(ctx, {
						type: 'pie',
						data: {
							labels: labels,
							datasets: [{
								label: 'تعداد نسخه‌های موجود',
								data: totalLoans,
								backgroundColor: 'rgba(86, 145, 255, 0.6)'
							}]
						},
						options: {
							indexAxis: 'y',
							responsive: true,
							scales: {
								x: { beginAtZero: true }
							}
						}
					});
				}
			});
		}

		fetchChartData(7);
		fetchTopBooks();
		fetchAndRenderPopularCategories();
	});
</script>
