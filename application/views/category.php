<header class="page-header">
	<div class="container-fluid">
		<h2 class="no-margin-bottom">Book Category</h2>
	</div>
</header>
<div class="container-fluid">
	<div class="table-agile-info">
		<?php if ($this->session->flashdata('message') != null):
			$messageType = $this->session->flashdata('messageType');
			echo "<br><div class='alert alert-$messageType alert-dismissible fade show' role='alert'>"
				. $this->session->flashdata('message') . "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			<span aria-hidden='true'>&times;</span>
			</button> </div>";
		endif; ?>
		<div class="card rounded-1 mt-3">
			<div class="card-header">
				<a href="#add" data-toggle="modal" class="btn btn-primary btn-sm rounded-1 pull-right"><i
						class="fa fa-plus"></i> Add New Category</a>
			</div>
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
							<td>Category Code</td>
							<td>Category Name</td>
							<td>Action</td>
						</tr>
					</thead>
					<tbody style="background-color: white;">
						<?php $no = 0;
						foreach ($allCategory as $category):
							$no++; ?>

							<tr>
								<td><?= $no ?></td>
								<td>#CA<?= $category->category_id ?></td>
								<td><?= $category->category_name ?></td>
								<td class="text-center">
									<a href="#edit" onclick="edit('<?= $category->category_id ?>')"
										class="btn btn-primary  btn-sm rounded-1" data-toggle="modal"><i
											class="fa fa-pencil"></i></a>
									<a href="<?= base_url('category/deleteCategory/' . $category->category_id) ?>"
										onclick="return confirm('Are you sure you want to delete this category?')"
										class="btn btn-danger btn-sm rounded-1"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="modal" id="add">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						Add New Category
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
					</div>
					<form action="<?= base_url('category/addCategory') ?>" method="post">
						<div class="modal-body">
							<div class="form-group row">
								<div class="col-sm-3 offset-1"><label>Category Name</label></div>
								<div class="col-sm-7">
									<input type="text" name="category_name" required class="form-control">
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
		<div class="modal fade" id="edit">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						Edit Category
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
					</div>
					<form action="<?= base_url('category/updateCategory') ?>" method="post">
						<div class="modal-body">
							<input type="hidden" name="category_id" id="category_id">
							<div class="form-group row">
								<div class="col-sm-3 offset-1"><label>Category Name</label></div>
								<div class="col-sm-7">
									<input type="text" name="category_name" id="category_name" required
										class="form-control">
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

	<script type="text/javascript">
		$(document).ready(function () {
			$('#example').DataTable();
		}
		);
		function edit(a) {
			$.ajax({
				type: "post",
				url: "<?= base_url() ?>category/getCategoryById/" + a,
				dataType: "json",
				success: function (data) {
					$("#category_code").val(data.category_id);
					$("#category_name").val(data.category_name);
					$("#category_id").val(data.category_id);
				}
			});
		}
	</script>
