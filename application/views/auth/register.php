<div class="row">
	<div class="push-25 col-md-6">
		<h4 class="text-center mt-20">Sing Up</h4>
		<!-- <div class="text-denger"><?php echo validation_errors(); ?></div> -->
		<form method="post" action="<?php echo base_url(); ?>auth/userRegister" id="form_register">
		<!-- <?php echo form_open('auth/userRegister'); ?> -->
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="name" placeholder="Enter name" class="form-control">
			<div class="text-danger"><?php echo form_error('name'); ?></div>
		</div>
		<div class="form-group">
			<label>Email</label>
			<input type="text" name="email" placeholder="Enter email" class="form-control">
			<div class="text-danger"><?php echo form_error('email'); ?></div>
		</div>
		<div class="form-group">
			<label>Username</label>
			<input type="text" name="username" placeholder="Enter username" class="form-control">
			<div class="text-danger"><?php echo form_error('username'); ?></div>
		</div>
		<div class="form-group">
			<label>Password</label>
			<input type="password" name="password" id="password" placeholder="Enter password" class="form-control">
			<div class="text-danger"><?php echo form_error('password'); ?></div>
		</div>
		<div class="form-group">
			<label>Confirm Password</label>
			<input type="password" name="cpassword" placeholder="Confirm password" class="form-control">
			<div class="text-danger"><?php echo form_error('cpassword'); ?></div>
		</div>
		<div class="text-right">
			<button type="submit" class="btn btn-primary">Register</button>
		</div>
	</form>
</div>
</div>

<script>
	$(document).ready(function(){
		$("#form_register").validate({
			rules: {
				name: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				username: {
					required: true
				},
				password: {
					required: true,
					minlength: 6,
					maxlength: 32
				},
				cpassword: {
					required: true,
					equalTo: '#password'
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
				username: {
					required: "Please enter username",
				},
				password: {
					required: "Please enter password",
					minlength: "Minimum 6 character required",
					maxlength: "maximum 32 character allowed"
				},
				cpassword: {
					required: "Please enter confirm password",
					equalTo: "Password confirmation dosent match"
				},
			}
		})
	})
</script>
