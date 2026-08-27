<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

    $action = isset($_GET['action']) ? $_GET['action'] : 'view';
    switch ($action) {
    case 'view':
        ?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-cubes"></i> Categories </h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active"><a href="index?insert_cat" class="btn btn-success">
                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Category</span>
                        </a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <!--- 2 row Starts --->
        <div class="col-lg-12">
            <!--- col-lg-12 Starts --->
            <div class="card">
                <!--- card Starts --->
                <div class="card-header">
                    <!--- card-header Starts --->
                    <h4 class="h4">
                        <i class="fa fa-money-bill-alt"></i> View All Categories
                    </h4>
                </div>
                <!--- card-header Ends --->
                <div class="card-body">
                    <!--- card-body Starts --->
                    <table class="table table-bordered">
                        <!--- table table-bordered table-hover Starts --->
                        <thead>
                            <tr>
                                <th>Category Id</th>
                                <th>Category Title</th>
                                <th>Category Description</th>
                                <th>Category Featured</th>
                                <th>Delete Category</th>
                                <th>Edit Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--- tbody Starts --->
                            <?php
                            $i = 0;
                            $get_cats = $db->select("categories");
                            while($row_cats = $get_cats->fetch()){
                            $cat_id = $row_cats->cat_id;
                            $cat_featured = $row_cats->cat_featured;
                            $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $adminLanguage));
                            $row_meta = $get_meta->fetch();
                            $cat_title = $row_meta->cat_title;
                            $cat_desc = $row_meta->cat_desc;
                            $i++;
                            ?>
                            <tr>
                                <td>
                                    <?= $i; ?>
                                </td>
                                <td>
                                    <?= $cat_title; ?>
                                </td>
                                <td width="400">
                                    <?= $cat_desc; ?>
                                </td>
                                <td>
                                    <?= $cat_featured; ?>
                                </td>
                                <td>
                                    <a href="index?delete_cat=<?= $cat_id; ?>"
                                        onclick="return confirm('Deleting this category will delete all its sub-categories. Do you wish to proceed?');"
                                        class="btn btn-danger">
                                        <i class="fa fa-trash text-white"></i> <span class="text-white">Delete</span>
                                    </a>
                                </td>
                                <td>
                                    <a href="index?edit_cat=<?= $cat_id; ?>" class="btn btn-success">
                                        <i class="fa fa-pencil text-white"></i> <span class="text-white">Edit Cat</span>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <!--- tbody Ends --->
                    </table>
                    <!--- table table-bordered table-hover Ends --->
                </div>
                <!--- card-body Ends --->
            </div>
            <!--- card Ends --->
        </div>
        <!--- col-lg-12 Ends --->
    </div>
    <!--- 2 row Ends --->
</div>
<?php
        break;
    case 'edit':
        if(isset($_GET['edit_cat'])){
$edit_id = $input->get('edit_cat');
$edit_cat = $db->select("categories",array('cat_id' => $edit_id));
if($edit_cat->rowCount() == 0){
  echo "<script>window.open('index?dashboard','_self');</script>";
}
$row_edit = $edit_cat->fetch();
$c_image = $row_edit->cat_image;
$c_featured = $row_edit->cat_featured;
$isS3 = $row_edit->isS3;
$enable_watermark = $row_edit->enable_watermark;
$get_meta = $db->select("cats_meta",array("cat_id" => $edit_id, "language_id" => $adminLanguage));
$row_meta = $get_meta->fetch();
$c_title = $row_meta->cat_title;
$c_desc = $row_meta->cat_desc;
$show_image = getImageUrl("categories",$row_edit->cat_image);
}
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-cubes"></i> Categories / Edit</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Edit Category</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <!--- 2 row Starts --->
        <div class="col-lg-12">
            <!--- col-lg-12 Starts --->
            <?php 
         $form_errors = Flash::render("form_errors");
         $form_data = Flash::render("form_data");
         if(is_array($form_errors)){
         ?>
            <div class="alert alert-danger">
                <!--- alert alert-danger Starts --->
                <ul class="list-unstyled mb-0">
                    <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
                    <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
                    <?php } ?>
                </ul>
            </div>
            <!--- alert alert-danger Ends --->
            <?php } ?>
            <div class="card">
                <!--- card Starts --->
                <div class="card-header">
                    <!--- card-header Starts --->
                    <h4 class="h4">
                        <i class="fa fa-money-bill-alt"></i> Edit Category
                    </h4>
                </div>
                <!--- card-header Ends --->
                <div class="card-body">
                    <!--- card-body Starts --->
                    <form action="" method="post" enctype="multipart/form-data">
                        <!--- form Starts --->
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-4 control-label"> Category Title : </label>
                            <div class="col-md-6">
                                <input type="text" name="cat_title" class="form-control" value="<?= $c_title; ?>"
                                    required>
                            </div>
                        </div>
                        <!--- form-group row Ends --->
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-4 control-label"> Category Description : </label>
                            <div class="col-md-6">
                                <textarea name="cat_desc" class="form-control" required=""><?= $c_desc; ?></textarea>
                            </div>
                        </div>
                        <!--- form-group row Ends --->
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-4 control-label">Featured Category : </label>
                            <div class="col-md-6">
                                <input type="radio" name="cat_featured" value="yes" required <?php
            if($c_featured == "yes"){
            echo "checked='checked'";
            }
            ?>>
                                <label> Yes </label>
                                <input type="radio" name="cat_featured" value="no" required <?php
            if($c_featured == "no"){
            echo "checked='checked'";
            }
            ?>>
                                <label> No </label>
                            </div>
                        </div>
                        <!--- form-group row Ends --->
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-4 control-label"> Category Image : </label>
                            <div class="col-md-6">
                                <input type="file" name="cat_image" class="form-control">
                                <br>
                                <?php if(!empty($c_image)){ ?>
                                <img src="<?= $show_image; ?>" width="70" height="55">
                                <?php }else{ ?>
                                <img src="../cat_images/empty-image.jpg" width="70" height="55">
                                <?php } ?>
                            </div>
                        </div>
                        <!--- form-group row Ends --->
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-4 control-label"> Enable Watermark : </label>
                            <div class="col-md-6">
                                <input type="radio" name="enable_watermark" value="1" required=""
                                    <?= ($enable_watermark == 1)?"checked":""; ?>>
                                <label> Yes </label>
                                <input type="radio" name="enable_watermark" value="0" required=""
                                    <?= ($enable_watermark == 0)?"checked":""; ?>>
                                <label> No </label>
                            </div>
                        </div>
                        <!--- form-group row Ends --->
                        <?php 
            if($videoPlugin == 1){ 
              include("../plugins/videoPlugin/admin/edit_cat.php");
            }
            ?>
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-6">
                                <input type="submit" name="update_cat" value="Update Category"
                                    class="btn btn-success form-control">
                            </div>
                        </div>
                        <!--- form-group row Ends --->
                    </form>
                    <!--- form Ends --->
                </div>
                <!--- card-body Ends --->
            </div>
            <!--- card Ends --->
        </div>
        <!--- col-lg-12 Ends --->
    </div>
    <!--- 2 row Ends --->
    <?php
include("includes/sanitize_url.php");
if(isset($_POST['update_cat'])){
	$rules = $vaidation = array(
	"cat_title" => "required",
	"cat_desc" => "required",
	"cat_featured" => "required");
	if(isset($_POST['video'])){
		$rules['reminder_emails'] = "number|required";
		$rules['missed_session_emails'] = "number|required";
		$rules['warning_message'] = "number|required";
	}
	$messages = array("cat_title" => "Category Title Is Required.","cat_desc" => "Category Description Is Required.","cat_featured" => "Your Must Need To Chose Category Featured As Yes Or No");
	$val = new Validator($_POST,$rules,$messages);
	if($val->run() == false){
		Flash::add("form_errors",$val->get_all_errors());
		Flash::add("form_data",$_POST);
		echo "<script> window.open(window.location.href,'_self');</script>";
	}else{
		$cat_title = $input->post('cat_title');
        $cat_url = slug($cat_title);
        $cat_desc = $input->post('cat_desc');
		$cat_featured = $input->post('cat_featured');
        $enable_watermark = $input->post('enable_watermark');
		$cat_image = $_FILES['cat_image']['name'];
		$tmp_cat_image = $_FILES['cat_image']['tmp_name'];
		$allowed = array('jpeg','jpg','gif','png','tif','ico','webp');
		$file_extension = pathinfo($cat_image, PATHINFO_EXTENSION);
		if(!in_array($file_extension,$allowed) & !empty($cat_image)){
			echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
		}else{
			if(empty($cat_image)){
				$cat_image = $c_image;
			}else{
            uploadToS3("cat_images/$cat_image",$tmp_cat_image);
            $isS3 = $enable_s3;
         }
			if($videoPlugin == 1){
				$video = $input->post('video');
				$reminder_emails = $input->post('reminder_emails');
				$missed_session_emails = $input->post('missed_session_emails');
				$warning_message = $input->post('warning_message');
				$update_cat = $db->update("categories",array("cat_url"=>$cat_url,"cat_image"=>$cat_image,"cat_featured"=>$cat_featured,"enable_watermark"=>$enable_watermark,"isS3"=>$isS3,"video"=>$video,"reminder_emails"=>$reminder_emails,"missed_session_emails"=>$missed_session_emails,"warning_message"=>$warning_message),array("cat_id"=>$edit_id));
			}else{
				$update_cat = $db->update("categories",array("cat_url"=>$cat_url,"cat_image"=>$cat_image,"cat_featured"=>$cat_featured,"enable_watermark"=>$enable_watermark,"isS3"=>$isS3),array("cat_id"=>$edit_id));
			}
			if($update_cat){
				$update_meta = $db->update("cats_meta",array("cat_title" => $cat_title,"cat_desc" => $cat_desc),array("cat_id" => $edit_id, "language_id" => $adminLanguage));
				$insert_log = $db->insert_log($admin_id,"cat",$edit_id,"updated");
				echo "<script>alert('One Category Has Been Updated.');</script>";
				echo "<script>window.open('index?view_cats','_self');</script>";
			}
		}
	}	
}
?>
</div>
<?php
        break;
    default:
        // Standardverhalten oder Fehlerbehandlung
        echo 'Ungültige Aktion';
        break;
}
?>
<?php } ?>