<html>
<head>

</head>
<body>
	<div class="container">
		<div class="jumbotron">
			<div class="row">
				<div class="col-md-3">
					<img src="<?php echo site_url(); ?>assets/images/blogs/<?php echo $post['image']; ?>" height="150" width="150">
				</div>
				<div class="col-md-9">
					<h5><?php echo $post['title']; ?></h5>
					<small>Posted On:<?php echo $post['created_at']; ?> in <?php echo $post['category_name']; ?></small>
					<p><?php echo $post['body']; ?></p>
				</div>
			</div>
			<?php
	if(sizeof($comments) > 0){
		echo '<p>Comments</p>';
	} else{
		echo '<p>No comments</p>';
	}
	 ?>
			<div id="viewComments">
				<?php foreach ($comments as $comment): ?>
				<div class="list-group">
					<a href="javascript:;" class="list-group-item list-group-item-action flex-column align-items-start">
						<div class="w-100 justify-content-between">
							<h5 class="mb-1"></h5>
							<small class="text-muted"><?php echo $comment['name'] ?></small>
						</div>
						<p class="mb-1"><?php echo $comment['comment'] ;?></p>
					</a>
				</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</body>
</html>
