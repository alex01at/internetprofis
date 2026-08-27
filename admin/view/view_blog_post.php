<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('../login','_self');</script>";
}else{
?>
<div class="main-container">
	<!--- container Starts --->
	<div class="row">
		<!-- row Starts -->
		<div class="col-lg-12">
			<!-- col-lg-12 Starts -->
			<div class="card card-default">
				<!-- card card-default Starts -->
				<div class="card-header">
					<ol class="text-right">
						<li class="active"><a href="index?insert_blog" class="btn btn-success">
								<i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Blog
									Post</span>
							</a></li>
					</ol>
					<!-- card-header Starts -->
					<h4 class="h3 mb-0">Posts</h4>
				</div><!-- card-header Ends -->
				<div class="card-body">
					<!-- card-body Starts -->
					<div class="table-responsive">
						<!--- table-responsive Starts -->
						<table class="table table-bordered table-hover table-striped">
							<!--- table table-bordered table-hover table-striped Starts -->
							<thead>
								<tr>
									<th>No</th>
									<th colspan="2">Post</th>
									<th>Actions:</th>
								</tr>
							</thead>
							<tbody>
								<?php
		$i = 0;
		$posts = $db->select("posts","","DESC");
		while($post = $posts->fetch()){
			$i++;
			$query = $db->select("posts_meta", array('post_id' => $post->id, 'language_id' => $adminLanguage));			
			$post_meta = $query->fetch();
			$title = !empty($post_meta->title) ? $post_meta->title: '';
			$author = !empty($post_meta->author) ? $post_meta->author: '';
			$content = !empty($post_meta->content) ? $post_meta->content: '';
			$url = preg_replace('#[ -]+#', '-', $title);
			/// Get Category Details
			$get_cat = $db->select("post_categories_meta",['cat_id'=>$post->cat_id, 'language_id' => $adminLanguage]);
			$row_cat = $get_cat->fetch();
			$cat_name = !empty($row_cat->cat_name) ? $row_cat->cat_name: '';
		?>
								<tr>
									<td><?= $i; ?></td>
									<td><img width="100" src="<?= getImageUrl("posts",$post->image); ?>"
											class="rounded"></td>
									<td width="800">
										<strong><?= $title; ?></strong>
										<p class="mt-2"><?= strip_tags(substr($content, 0,300)); ?>...</p>
										<p class="text-lead mb-0">Published on: <?= $post->date_time; ?> | Category:
											<?= $cat_name; ?></p>
									</td>
									<td>
										<!-- Button, um das Modal anzuzeigen -->
										<button class="btn btn-danger btn-sm" data-toggle="modal"
											data-target="#blogPostActionsModal<?= $post->id; ?>">
											 Actions
										</button>
										<!-- Das Modal -->
										<div class="modal fade" id="blogPostActionsModal<?= $post->id; ?>" tabindex="-1"
											role="dialog" aria-labelledby="blogPostActionsModalLabel<?= $post->id; ?>"
											aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content m-1 text-center">
													<div class="modal-header">
														<h5 class="modal-title"
															id="blogPostActionsModalLabel<?= $post->id; ?>">Post Actions
														</h5>
														<button type="button" class="close" data-dismiss="modal"
															aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<a class="btn btn-success"
															href="../blog/post?id=<?= $post->id; ?>&lang=<?= $adminLanguage; ?>"
															target="_blank">
															<i class="fa fa-eye"></i> Live Preview
														</a>
														<a class="btn btn-success"
															href="index?edit_blog=<?= $post->id; ?>">
															<i class="fa fa-pencil"></i> Edit
														</a>
														<a class="btn btn-danger"
															href="index?delete_blog=<?= $post->id; ?>"
															onclick="if(!confirm('Are you sure you want to delete selected item.')){ return false; }">
															<i class="fa fa-trash"></i> Delete
														</a>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary"
															data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
										<!--- dropdown Ends --->
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<!--- table table-bordered table-hover table-striped Starts -->
					</div>
					<!--- table-responsive Ends -->
				</div><!-- card-body Ends -->
			</div><!-- card card-default Ends -->
		</div>
	</div>
</div>
<!--- container Ends --->
<?php } ?>