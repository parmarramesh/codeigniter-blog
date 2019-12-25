<h4><?php $title; ?></h4><br>

<?php if ($this->session->flashdata('alert_message')):
	$time = time(); ?>
		<div class="alert fontsize_16 alert-<?php echo $this->session->flashdata('alert-type') ?>" id="alert-<?php echo $time ?>" >

						<?php
						if ($this->session->flashdata('alert-type') == 'success') {
								echo '<i class="icon fa fa-check"></i>';
						} else {
								echo '<i class="icon fa fa-ban"></i>';
						}
						?>
						<?php echo $this->session->flashdata('alert_message') ?>

		</div>
<script>
		setTimeout(function () {
				$('#alert-<?php echo $time ?>').hide().remove();
		}, 5000)
</script>
<?php endif ?>

<div class="jumbotron">
	<div class="row">
		<div class="col-md-3">
			<img src="<?php echo site_url(); ?>assets/images/blogs/<?php echo $post['image']; ?>" height="150" width="150">
		</div>
		<div class="col-md-9">
			<h5><?php echo $post['title']; ?></h5>
			<small>Posted On:<?php echo $post['created_at']; ?> in <?php echo $post['category_name']; ?></small>
			<p><?php echo $post['body']; ?></p>
			<hr class="my-4">
			<a class="btn btn-primary  float-left mr-15" href="<?php echo base_url();?>posts/edit/<?php echo $post['slug'];?>">Edit</a>

			<a href="javascript:;" class="btn btn-danger" onclick="removeBlogs('<?php echo base_url();?>posts/delete/<?php echo $post['id'];?>')">Delete</a>
			<a class="btn btn-dark float-left mr-15" href="<?php echo base_url();?>posts/exportToWord/<?php echo $post['slug'];?>"> Export To Word</a>
			<a class="btn btn-dark float-left mr-15" href="<?php echo base_url();?>posts/exportToPdf/<?php echo $post['slug'];?>"> Export To Pdf</a>
		</div>
	</div>
	<hr class="my-4">
	<?php
	if(sizeof($comments) > 0){
		echo '<h4>Comments</h4>';
	} else{
		echo '<h4>No comments</h4>';
	}
	 ?>
	<div id="viewComments">
	<?php foreach ($comments as $comment): ?>
		<div class="list-group">
			<a href="javascript:;" class="list-group-item list-group-item-action flex-column align-items-start">
				<div class="d-flex w-100 justify-content-between">
					<h5 class="mb-1"></h5>
					<small class="text-muted"><?php echo $comment['name'] ?></small>
				</div>
				<p class="mb-1"><?php echo $comment['comment'] ;?></p>
			</a>
		</div>
	<?php endforeach ?>
</div>

	<hr class="my-4">
	<div class="row">
		<div class="col-md-12">
			<h5>Add Comment</h5>
			<!-- <form method="post" id="form_add_comment" action="<?php echo site_url(); ?>posts/commentAdd/<?php echo $post['id']; ?>"> -->
				<form method="post" id="form_add_comment">
					<input type="hidden" name="slug" value="<?php echo $post['slug'] ?>">
					<input type="hidden" name="pid" id="pid" value="<?php echo $post['id'] ?>">
					<div class="form-group">
						<label>Name</label>
						<input type="text" name="name" placeholder="Enter name" class="form-control">
						<div class="text-danger"><?php echo form_error('name'); ?></div>
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="text" name="email" class="form-control" placeholder="Enter Email">
						<div class="text-danger"><?php echo form_error('email'); ?></div>
					</div>
					<div class="form-group">
						<label>Comments</label>
						<textarea name="comment" class="form-control" placeholder="Enter Comment"></textarea>
						<div class="text-danger error_msg"><?php echo form_error('comment'); ?></div>
					</div>
					<button type="submit" class="btn btn-primary btn_comment">Submit</button>
				</form>
			</div>
		</div>
	</div>
	<script>
		function removeBlogs(url_val){
			if(confirm('Are you sure you want to delete this blog ?') == false)
			{
				return false;
			}
			else
			{
				location.href=url_val;
			}
		}

		$("#form_add_comment").validate({
			rules: {
				name: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				comment: {
					required: true
				}
			},
			messages: {
				name: {
					required: "Please enter name",
				},
				email: {
					required: "Please enter email",
					email: "Please enter valid email"
				},
				comment: {
					required: "Please enter username",
				}
			},
			submitHandler: function (form) {
				var formdata =  $('#form_add_comment').serialize();

				$.ajax({
					type: 'POST',
					url: '<?php echo site_url("posts/commentAdd/"); ?>',
					dataType: 'json',
					data: formdata,
					beforeSend: function() {
						 $('btn_comment').attr("disabled","disabled");
					},
					success: function(response) {
						$('btn_comment').attr("disabled",false);
						if(response.code == 1){

							var newComment = '<div class="list-group">' +
								'<a href="javascript:;" class="list-group-item list-group-item-action flex-column align-items-start">'+
									'<div class="d-flex w-100 justify-content-between">'+
										'<h5 class="mb-1"></h5>'+
										'<small class="text-muted">' + response.data.name + '</small>'+
									'</div>'+
									'<p class="mb-1">' + response.data.comment + '</p>'+
								'</a>'+
							'</div>';
							$('#viewComments').prepend(newComment);
							$.notify(response.message, "success");
							$("#form_add_comment")[0].reset();
						} else if(response.code == 0){
							$.notify(response.message, "danger");
						}
					},
					error: function(err) {
						$('btn_comment').attr("disabled",false);
						$.notify('Some error occured, Please try again!', "danger");
					},
				});
			}
		});
	</script>
