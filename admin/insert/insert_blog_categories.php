<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
?>
<div class="main-container">
    <div class="row">
        <!--- 2 row Starts --->
        <div class="col-lg-12">
            <!--- col-lg-12 Starts --->
            <div class="card">
                <!--- card Starts --->
                <div class="card-header">
                    <div class="float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li class="active"><a href="index?blog_categories" class="btn btn-danger">
                                        <i class=" text-white"></i> <span class="text-white">Cancel</span>
                                    </a></li>
                            </ol>
                        </div>
                    </div>
                    <!--- card-header Starts --->
                    <h4 class="h4">
                        <i class="fa fa-money-bill-alt"></i> View All Categories
                    </h4>
                </div>
				<div class="card-body">
					<!-- card-body Starts -->
					<h3>New Category</h3>
					<form action="" method="post" enctype="multipart/form-data">
						<div class="form-group mt-2">
							<label for="cat_name">Name</label>
							<input class="form-control input-md" type="text" name="cat_name" placeholder="Name"
								required="">
						</div>
						<div class="form-group mt-2">
							<label for="cat_image">Image</label>
							<input class="form-control input-md" type="file" name="cat_image">
						</div>
						<div class="form-group">
							<input class="form-control btn btn-success" name="insert" type="submit"
								value="Insert New Category">
						</div>
					</form>

<?php } ?>                    