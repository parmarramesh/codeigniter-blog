<html>

<head>
	<title>
		Demo
	</title>
	<link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">
	<!-- Font Awesome -->
  <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="https://bootswatch.com/_vendor/popper.js/dist/umd/popper.min.js"></script>
	<script src="https://bootswatch.com/_vendor/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/notify.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
</head>

<body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarColor02">

				<?php if ($this->session->userdata('isLogin')):?>
				<ul class="navbar-nav mr-auto">
					<li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>pages/view"><?php echo $this->lang->line('home'); ?></a></li>
					<li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>blogs/create"><?php echo $this->lang->line('post_blog'); ?></a></li>
					<li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>category/create"><?php echo $this->lang->line('add_category'); ?></a></li>
					<li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>blogs"><?php echo $this->lang->line('blogs'); ?></a></li>
					<li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>category"><?php echo $this->lang->line('category'); ?></a></li>
				</ul>
				<?php if ($this->session->userdata('username')):?>
				<span class="mr-15">Welcome <?php echo $this->session->userdata('username') ?></span>
				<?php endif ?>
				<a href="<?php echo base_url(); ?>auth/logout" class="btn btn-secondary">Logout</a>
				<?php endif; ?>
				<?php if (!$this->session->userdata('isLogin')):?>
				<ul class="navbar-nav mr-auto"></ul>
				<a href="<?php echo base_url(); ?>auth/login" class="btn btn-secondary mr-15">Login</a>
				<a href="<?php echo base_url(); ?>auth/register" class="btn btn-secondary mr-15">Register</a>
				<?php endif; ?>

        <?php if ($this->session->userdata('language')):?>
				<div class="ml-15">
					<select class="form-control" id="select_langguage" name="select_langguage">
						<option value="english" <?php echo $this->session->userdata('language') == 'english'? 'selected': '' ?>>English</option>
						<option value="gujarati" <?php echo $this->session->userdata('language') == 'gujarati'? 'selected': '' ?>>Gujarati</option>
					</select>
				</div>
        <?php endif; ?>

			</div>
		</div>
		</div>
	</nav>
	<div class="container">
		<?php if ($this->session->flashdata('user_registered')): ?>
		<?php echo '<p class="alert alert-success mt-15">'.$this->session->flashdata('user_registered').'</p>'; ?>
		<?php endif; ?>
		<?php if ($this->session->flashdata('login_success')): ?>
		<?php echo '<p class="alert alert-success mt-15">'.$this->session->flashdata('login_success').'</p>'; ?>
		<?php endif; ?>
		<?php if ($this->session->flashdata('login_failed')): ?>
		<?php echo '<p class="alert alert-danger mt-15">'.$this->session->flashdata('login_failed').'</p>'; ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('category_created')): ?>
		<?php echo '<p class="alert alert-success mt-15">'.$this->session->flashdata('category_created').'</p>'; ?>
		<?php endif; ?>
		<?php if ($this->session->flashdata('blog_created')): ?>
		<?php echo '<p class="alert alert-success mt-15">'.$this->session->flashdata('blog_created').'</p>'; ?>
		<?php endif; ?>
		<?php if ($this->session->flashdata('blog_updated')): ?>
		<?php echo '<p class="alert alert-success mt-15">'.$this->session->flashdata('blog_updated').'</p>'; ?>
		<?php endif; ?>
