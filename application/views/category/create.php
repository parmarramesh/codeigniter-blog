<div class="row mt-15">
	<div class="push-25 col-md-6">
		<h4><?php echo $title ?></h4>
		<!-- <div class="text-denger"><?php echo validation_errors(); ?></div> -->
		<div class="jumbotron">
			<form method="post" id="add_category_form" action="create">
				<!-- <form method="post" id="add_category_form"> -->

				<?php foreach ($languages as $lang): ?>
				<h5><?php echo strtoupper($lang->name) ?></h5>
				<div class="form-group">
				<label>Category Name</label>
				<input type="text" name="categoryName_<?php echo $lang->id; ?>" value="<?php echo set_value('categoryName_' . $lang->id); ?>" placeholder="Enter Category Name" class="form-control">
				<div class="text-danger"><?php echo form_error('categoryName_' . $lang->id); ?></div>
				</div>
				<hr>
				<?php endforeach;?>
				<button type="submit" id="btnCategory" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// $("#add_category_form").validate({
		// 	rules: {
		// 		categoryName: {
		// 			required: true
		// 		},
		// 	},
		// 	messages: {
		// 		categoryName: {
		// 			required: "Please enter category name",
		// 		}
		// 	}
		// });

// 		$('#form-add_category_form').validate({
// 	rules: {
// 				"categoryName[]": "required",
// 		},
// });
	})
</script>
