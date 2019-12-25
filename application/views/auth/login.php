<div class="row">
	<div class="push-25 col-md-6">
		<h4 class="text-center mt-20">Sing In</h4>
		<!-- <div class="text-denger"><?php echo validation_errors(); ?></div> -->
		<form method="post" action="<?php echo base_url(); ?>auth/userLogin" id="form_login">
		<?php echo form_open('auth/userLogin'); ?>

		<div class="form-group">
			<label>Username</label>
			<input type="text" name="username" placeholder="Enter username" class="form-control">
			<div class="text-danger"><?php echo form_error('username'); ?></div>
		</div>
		<div class="form-group">
			<label>Password</label>
			<input type="password" name="password" placeholder="Enter password" class="form-control">
			<div class="text-danger"><?php echo form_error('password'); ?></div>
</div>
<div class="text-right">
<button type="submit" class="btn btn-primary">Login</button>
</div>
</form>
</div>
</div>

	<script>
		$(document).ready(function(){
			$("#form_login").validate({
				rules: {
					username: {
						required: true
					},
					password: {
						required: true
					}
				},
				messages: {
					username: {
						required: "Please enter username",
					},
					password: {
						required: "Please enter password",
					}
				}
			})
		})
	</script>
