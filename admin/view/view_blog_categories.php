<div class="main-container">
	<div class="row">
		<!--- 2 row Starts --->
		<div class="col-lg-12">
			<!--- col-lg-12 Starts --->
			<div class="card">
				<!--- card Starts --->
				<div class="card-header">
					<!--- card-header Starts --->
					<ol class="text-right">
						<li class="active"><a href="index?insert_blog_categories" class="btn btn-success">
								<i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Blog
									Category</span>
							</a></li>
					</ol>
					<h4 class="h4">
						View Delivery Times
					</h4>
					<div class="table-responsive">
						<!--- table-responsive Starts -->
						<table class="table table-bordered table-hover table-striped">
							<!--- table table-bordered table-hover table-striped Starts -->
							<thead>
								<tr>
									<th>No</th>
									<th>Category Name</th>
									<th>Date Added</th>
									<!-- <th>Added By</th> -->
									<th>Language</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
		$i = 0;
		$categories = $db->select("post_categories","","DESC");
		while($cat = $categories->fetch()){
			$i++;
			$cat_id = $cat->id;
			// $insert = $db->insert("post_categories_meta",array("cat_id"=>$cat_id,"cat_name"=>$cat->cat_name,"language_id"=>$adminLanguage,"cat_creator"=>$cat->cat_creator));
			$post_category_meta = $db->select("post_categories_meta",array("cat_id" => $cat_id, "language_id" => $adminLanguage))->fetch();			
			$language_title = $db->query("select * from languages where id = ".$post_category_meta->language_id)->fetch()->title;
			?>
								<tr>
									<td><?= $i; ?></td>
									<td><?= $post_category_meta->cat_name; ?></td>
									<td><?= $cat->date_time; ?></td>
									<td><?= $language_title; ?></td>
									<!-- <td><?= $cat->cat_creator; ?></td> -->
									<td>
										<!-- Button, um das Modal anzuzeigen -->
										<button class="btn btn-sm btn-danger" data-toggle="modal"
											data-target="#blogCatActionsModal<?= $cat->id; ?>">
											Actions
										</button>
										<!-- Das Modal -->
										<div class="modal fade" id="blogCatActionsModal<?= $cat->id; ?>" tabindex="-1"
											role="dialog" aria-labelledby="blogCatActionsModalLabel<?= $cat->id; ?>"
											aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title"
															id="blogCatActionsModalLabel<?= $cat->id; ?>">Category
															Actions</h5>
														<button type="button" class="close" data-dismiss="modal"
															aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-1 text-center">
														<a class="btn btn-success"
															href="index?edit_blog_cat=<?= $cat->id; ?>">
															<i class="fa fa-pencil"></i> Edit
														</a>
														<a class="btn btn-danger"
															href="index?delete_blog_cat=<?= $cat->id; ?>"
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
					</div>
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
<?php 
if(isset($_POST['insert'])){
	$data = $input->post();
	$data['date_time'] = date("F d, Y");
	unset($data['insert']);	
	$cat_image = $_FILES['cat_image']['name'];
	$tmp_cat_image = $_FILES['cat_image']['tmp_name'];
	$allowed = array('jpeg','jpg','gif','png','tif','ico','webp');
	$file_extension = pathinfo($cat_image, PATHINFO_EXTENSION);
	if(!in_array($file_extension,$allowed) & !empty($cat_image)){
	   echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
	}else{
		uploadToS3("images/blog_cat_images/$cat_image",$tmp_cat_image);      
		$isS3 = $enable_s3;
		$post_categories = $db->insert("post_categories",array("date_time"=>$data['date_time'],'cat_image' => $cat_image, 'isS3'=> $isS3));
		if($post_categories){
			$insert_id = $db->lastInsertId();
			$get_languages = $db->select("languages");
			while($row_languages = $get_languages->fetch()){
				$id = $row_languages->id;
				$insert = $db->insert("post_categories_meta",["cat_id"=>$insert_id,"language_id"=>$id]);
			}
			unset($data['date_time']);
			$update_meta = $db->update("post_categories_meta",$data,array("cat_id" => $insert_id, "language_id" => $adminLanguage));
			$insert_log = $db->insert_log($admin_id,"post_cat",$insert_id,"updated");
			echo "<script>alert('One Post Category has been Inserted Successfully.');</script>";
			echo "<script>window.open('index?post_categories','_self');</script>";
		}
	}
}
?>