<header class="page-header">
	<div class="container-fluid">
		<h2 class="no-margin-bottom">Book Details</h2>
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
							<td>Book Title</td>
							<td>Cover</td>
							<td>Year</td>
							<td>Price</td>
							<td>Category</td>
							<td>Publisher</td>
							<td>Author</td>
							<td>Stock</td>
							<?php if ($this->session->userdata('level') == 'admin'): ?>
								<td>Action</td>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody style="background-color: white;">
						<?php $no = 0;
						foreach ($get_book as $book):
							$no++; ?>

							<tr>
								<td><?= $no ?></td>
								<td><?= $book->book_title ?></td>
								<td><img src="<?= base_url('assets/picProduct/' . $book->book_img) ?>" style="width:40px">
								</td>
								<td><?= $book->year ?></td>
								<td>$<?= number_format($book->price) ?></td>
								<td><?= $book->category_name ?></td>
								<td><?= $book->publisher ?></td>
								<td><?= $book->name ?></td>
								<td><?= $book->stock ?></td>
								<?php if ($this->session->userdata('level') == 'admin'): ?>
									<td class="text-center">
										<a href="#saveBook" onclick="edit('<?= $book->book_id ?>')"
											class="btn btn-primary btn-sm rounded-1" data-toggle="modal"><i
												class="fa fa-pencil"></i></a>
										<a href="<?= base_url('book/deleteBook/' . $book->book_id) ?>"
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
					<span class="title-modal">Add New Book</span>
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
				</div>
				<form action="<?= base_url('book/saveBook') ?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="book_id" id="book_id">
					<div class="modal-body">
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Book Title</label></div>
							<div class="col-sm-7">
								<input type="text" name="book_title" id="book_title" required="form-control"
									class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Year</label></div>
							<div class="col-sm-7">
								<input type="number" name="year" id="year" required="form-control" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Price</label></div>
							<div class="col-sm-7">
								<input type="number" name="price" id="price" required="form-control"
									class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Category</label></div>
							<div class="col-sm-7">
								<select name="category_id" id="category_id" required="form-control"
									class="form-control">
									<?php foreach ($category as $kat): ?>
										<option value="<?= $kat->category_id ?>">
											<?= $kat->category_name ?>
										</option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Cover Photo</label></div>
							<div class="col-sm-7">
								<input type="file" name="book_img" id="book_img" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Publisher</label></div>
							<div class="col-sm-7">
								<input type="text" name="publisher" id="publisher" required="form-control"
									class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Author</label></div>
							<div class="col-sm-7">
								<select name="author_id" id="author_id" required="form-control"
									class="form-control">
									<?php foreach ($authors as $author): ?>
										<option value="<?= $author->author_id ?>">
											<?= $author->name ?>
										</option>
									<?php endforeach ?>
								</select>
							</div>
							<!-- <div class="col-sm-3 offset-1"><label>Author</label></div>
							<div class="col-sm-7">
								<input type="text" name="author" id="author" required="form-control"
									class="form-control">
							</div> -->
						</div>
						<div class="form-group row">
							<div class="col-sm-3 offset-1"><label>Stock</label></div>
							<div class="col-sm-7">
								<input type="number" name="stock" id="stock" required="form-control"
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

	function add() {
		document.querySelector('.title-modal').innerText = 'Add New Book';
	}

	function edit(id) {
		document.querySelector('.title-modal').innerText = 'Edit Book';
		$.ajax({
			type: "post",
			url: "<?= base_url() ?>book/getBookById/" + id,
			dataType: "json",
			success: function (data) {
				$("#book_id").val(data.book_id);
				$("#book_title").val(data.book_title);
				$("#year").val(data.year);
				$("#price").val(data.price);
				$("#category").val(data.category_id);
				$("#publisher").val(data.publisher);
				$("#author_id").val(data.author_id);
				$("#stock").val(data.stock);
			}
		});
	}
</script>
