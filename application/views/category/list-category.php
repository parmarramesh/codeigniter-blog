
<!-- <?php foreach ($category as $cat): ?>
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-6"><a href="<?php echo site_url('/category/posts/'.$cat['cid']); ?>"><?php echo $cat['name'] ?></a></div>
				<div class="col-md-6">
					<button class="btn btn-danger" href="<?php echo base_url(); ?>category/delete/<?php echo $cat['cid']; ?>">Delete</button>
					<a href="javascript:;" onclick="isconfirm('<?php echo site_url('category/delete/'.$cat['cid']); ?>');">Delete</a>
				</div>
			</div><hr>
		</div>
	</div>
	<?php endforeach ?> -->
	<div class="col-md-6 push-25">
		<h4 class="mt-15"><?php echo $title; ?></h4><br>
		<ul class="list-group">
			<?php foreach ($category as $cat): ?>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<a href="<?php echo site_url('/category/posts/'.$cat['cid']); ?>"><?php echo $cat['category_name'] ?></a>
							<span class="badge"><button class="text-danger fontsize_16" href="javascript:;" onclick="isconfirm('<?php echo site_url('category/delete/'.$cat['cid']); ?>');">X</button></span>
						</li>
			<?php endforeach ?>
		</ul>

		<script>
			function isconfirm(url_val){
				if(confirm('Are you sure you want to delete this slider?') == false)
				{
					return false;
				}
				else
				{
					location.href=url_val;
				}
			}
		</script>
