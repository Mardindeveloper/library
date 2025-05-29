<header class="page-header">
	<div class="container-fluid">
		<h2 class="no-margin-bottom">Author Details</h2>
	</div>
</header>

<div class="table-agile-info">
	<div class="container-fluid my-3">
		<?php if ($this->session->flashdata('message') != null) {
			echo "<br><div class='alert alert-success alert-dismissible fade show' role='alert'>"
				. $this->session->flashdata('message') . "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			<span aria-hidden='true'>&times;</span>
			</button> </div>";
		} ?>
		<br>
		<div class="card rounded-1 shadow">
			<?php if ($this->session->userdata('level') == 'admin'): ?>
				<div class="card-header">
					<a href="#saveBook" onclick="add()" data-toggle="modal"
						class="btn btn-primary btn-sm rounded-1 pull-right"><i class="fa fa-plus"></i> Add New Book</a>
				</div>
			<?php endif; ?>
			<div class="card-body">
				<table class="table table-hover table-bordered" id="author" ui-options=ui-options="{
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
							<td>Author Name</td>
							<td>Author’s Book Count</td>
							<?php if ($this->session->userdata('level') == 'admin'): ?>
								<td>Action</td>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody style="background-color: white;">
						<?php $no = 0;
						foreach ($authors as $author):
							$no++; ?>
							<tr>
								<td><?= $no ?></td>
								<td><?= $author->name ?></td>
								<td><?= $author->total_books ?></td>
								<?php if ($this->session->userdata('level') == 'admin'): ?>
									<td class="text-center">
										<a href="#saveBook" onclick="edit('<?= $author->author_id ?>')"
											class="btn btn-primary btn-sm rounded-1" data-toggle="modal"><i
												class="fa fa-pencil"></i></a>
										<a href="<?= base_url('book/deleteBook/' . $author->author_id) ?>"
											onclick="return confirm('Are you sure to delete this book?')"
											class="btn btn-danger btn-sm rounded-1"><i class="fa fa-trash"></i></a>
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="modal" id="saveBook">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="title-modal">Add New Author</span>
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
				</div>
				<form action="<?= base_url('author/saveAuthor') ?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="author_id" id="author_id">
					<div class="modal-body">
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Author Name</label></div>
							<div class="col-sm-7">
								<input type="text" name="name" id="author_name" required="form-control"
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

<script>
	$(document).ready(function () {
		$('#author').DataTable();
	});

	function add() {
		document.querySelector('.title-modal').innerText = 'Add New Author';
	}

	function edit(id) {
		document.querySelector('.title-modal').innerText = 'Edit Book';
		$.ajax({
			type: "post",
			url: "<?= base_url() ?>author/getAuthorById/" + id,
			dataType: "json",
			success: function (data) {
				$("#author_id").val(data.author_id);
				$("#author_name").val(data.name);
			}
		});
	}
</script>
