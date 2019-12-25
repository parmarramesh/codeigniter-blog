<div class="row mt-15">
	<div class="push-25 col-md-6">
		<h4><?php echo $title ?></h4>
		<!-- <div class="text-denger"><?php echo validation_errors(); ?></div> -->
		<form method="post" id="form_add_category" action="create" enctype="multipart/form-data">
		<!-- <?php echo form_open('blogs/create'); ?> -->
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="title" placeholder="Enter Title" class="form-control">
			<div class="text-danger"><?php echo form_error('title'); ?></div>
		</div>
		<div class="form-group">
			<label>Body</label>
			<textarea name="body" class="form-control"  placeholder="Enter Body"></textarea>
			<div class="text-danger"><?php echo form_error('body'); ?></div>
		</div>
		<div class="form-group">
			<label>Category</label>
			<select class="form-control" name="category">
				<option value="">Select Category</option>
				<?php foreach ($categories as $category) { ?>
					<option value="<?php echo $category['cid']; ?>"><?php echo $category['category_name'] ?></option>
					<?php
				} ?>
			</select>
			<div class="text-danger"><?php echo form_error('category'); ?></div>
		</div>
		<div class="form-group">
			<label>Upload Image</label>
			<input type="file" name="userfile" size="20">
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>
</div>

	<script>
		$(document).ready(function(){
			$("#form_add_category").validate({
				rules: {
					title: {
						required: true
					},
					body: {
						required: true
					},
					category: {
						required: true
					},
					userfile: {
						extension: "gif|jpg|jpeg|png"
					},
				},
				messages: {
					title: {
						required: "Please enter title text",
					},
					body: {
						required: "Please enter body text",
					},
					category: {
						required: "Please select category",
					},
					userfile: {
						extension: "Please enter jpg, png, or gif image"
					}
				}
			})
		})
	</script>
