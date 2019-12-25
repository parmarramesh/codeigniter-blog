<div class="row">
	<div class="push-25 col-md-6">
		<h4><?php echo $title ?></h4>
		<div class="text-denger"><?php echo validation_errors(); ?></div>
		<form method="post" action="<?php echo site_url(); ?>blogs/update" id="form_update_post"  enctype="multipart/form-data">
		<!-- <?php echo form_open('blogs/update'); ?> -->
		<input type="hidden" name="bid" value="<?= $post['id']; ?>">
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="title" placeholder="Enter Title" value="<?= $post['title']; ?>" class="form-control">
		</div>
		<div class="form-group">
			<label>Title</label>
			<textarea name="body" class="form-control"  placeholder="Enter Body"><?= $post['body']; ?></textarea>
		</div>
		<div class="form-group">
			<label>Category</label>
			<select class="form-control" name="category">
				<?php foreach ($categories as $category) { ?>
					<option value="<?php echo $category['cid']; ?>" <?php echo $category['cid'] == $post['cid'] ? 'selected' : '' ?> ><?php echo $category['category_name'] ?></option>
					<?php
				} ?>
			</select>
		</div>
		<div class="form-group">
			<label>Upload Image</label>
			<input type="file" name="userfile" size="20">
		</div>
		<button type="submit" class="btn btn-primary">Update</button>
	</form>
</div>
</div>

	<script>
		$(document).ready(function(){
			$("#form_update_post").validate({
				rules: {
					title: {
						required: true
					},
					body: {
						required: true
					},
					category: {
						required: true
					}
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
					}
				}
			})
		})
	</script>
