<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('../../login','_self');</script>";
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
					<!-- card-header Starts -->
					
					<h4 class="h3">View Suggested Categories</h4>
				</div><!-- card-header Ends -->
				<div class="card-body">
					<!-- card-body Starts -->
					<div class="table-responsive">
						<!--- table-responsive Starts -->
						<table class="table table-bordered table-hover table-striped">
							<!--- table table-bordered table-hover table-striped Starts -->
							<thead>
								<tr>
									<th>ID</th>
									<th>User</th>
									<th>Content</th>
									<th>Delete:</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 0;
								$suggest = $db->select("suggest_category","","DESC");
								while($sug = $suggest->fetch()){
								$user = $db->select("sellers",["seller_id"=>$sug->seller_id])->fetch()->seller_user_name;
								$i++;
								?>
								<tr>
									<td><?= $i; ?></td>
									<td>
										<?= $user; ?>
									</td>
									
									<td width="560"><?= $sug->content; ?></td>
									<td>
										<a class="btn text-white btn-danger" href="index?delete_suggestion=<?= $sug->id; ?>"
											onclick="if(!confirm('Are you sure you want to delete selected item.')){ return false; }">
											<i class="fa fa-trash"></i> Delete
										</a>
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
		</div><!-- col-lg-12 Ends -->
	</div><!-- row Ends -->
</div>
<!--- container Ends --->
<?php } ?>