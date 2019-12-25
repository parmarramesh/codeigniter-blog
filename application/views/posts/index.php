<div class="row mt-15 mb-15">
<div class="col-md-6">
	<h4 class=""><?= $title ?></h4>
</div>
<div class="col-md-6 text-right">
	<a class="btn btn-secondary" href="<?php echo site_url('/posts/exportReport'); ?>">Export To Excel</a>
</div>
</div>

<?php if(sizeof($posts) == 0){
	echo '<h4>No post found</h4>';
} ?>
<?php foreach ($posts as $post): ?>
	<div class="jumbotron ptb-20">
		<div class="row">
			<div class="col-md-2">
				<img src="<?php echo site_url(); ?>assets/images/blogs/<?php echo $post['image']; ?>" height="100" width="100">
			</div>
			<div class="col-md-10">
				<h5><?php echo $post['title']; ?></h5>
						<small>Posted On:<?php echo $post['created_at']; ?> in <?php echo $post['category_name']; ?></small>
				<p><?php echo $post['body']; ?></p>
				<hr class="">
				<a href="<?php echo site_url('/blogs/'.$post['slug']); ?>">Read More</a>
				<br>
			</div>
		</div>
	</div>
<?php endforeach ?>
<div class="row">
	<div class="col-md-12 text-center">
		<div class="pagination">
			<?php echo $this->pagination->create_links(); ?>
		</div>
	</div>
</div>
