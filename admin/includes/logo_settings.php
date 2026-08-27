<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('../../login','_self');</script>";
}else{
	
$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$site_favicon = getImageUrl2("general_settings","site_favicon",$row_general_settings->site_favicon);
$site_logo_type = $row_general_settings->site_logo_type;
$site_logo_text = $row_general_settings->site_logo_text;
$enable_mobile_logo = $row_general_settings->enable_mobile_logo;
$site_logo_image = getImageUrl2("general_settings","site_logo_image",$row_general_settings->site_logo_image);
$site_mobile_logo = getImageUrl2("general_settings","site_mobile_logo",$row_general_settings->site_mobile_logo);
$site_logo = getImageUrl2("general_settings","site_logo",$row_general_settings->site_logo);
$s_favicon = $row_general_settings->site_favicon;
$s_logo_image = $row_general_settings->site_logo_image;
$s_mobile_logo = $row_general_settings->site_mobile_logo;
$s_logo = $row_general_settings->site_logo;
$s_watermark = $row_general_settings->site_watermark;


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
					
					<h4 class="h3">Logo Settings</h4>
				</div><!-- card-header Ends -->
				<div class="card-body">
					<!-- card-body Starts -->
<form method="post" enctype="multipart/form-data"><!--- form Starts --->
					
<div class="form-group row">
<!--- form-group row Starts --->
<label class="col-md-3 control-label"> Site Favicon : </label>
<div class="col-md-6">
<div class="input-group">
<span class="input-group-addon"><b><i class="fa fa-paper-plane"></i></b></span>
<input type="file" name="site_favicon" class="form-control">
</div>
<img class="mt-1" src="<?= $site_favicon; ?>" width="30" height="30">
</div>
</div>
<!--- form-group row Ends --->

<div class="form-group row">
<!--- form-group row Starts --->
<label class="col-md-3 control-label"> Site Logo Type : </label>
<div class="col-md-6">
<select name="site_logo_type" class="form-control site_logo_type">
<?php if($site_logo_type == "text"){ ?>
<option value="text"> Text </option>
<option value="image"> Image </option>
<?php }elseif($site_logo_type == "image"){ ?>
<option value="image"> Image </option>
<option value="text"> Text </option>
<?php } ?>
</select>
</div>
</div>
<!--- form-group row Ends --->

<div class="form-group row site_logo_text">
<!--- form-group row Starts --->
<label class="col-md-3 control-label"> Site Logo Text : </label>
<div class="col-md-6">
<div class="input-group">
<span class="input-group-addon">
<b><i class="fa fa-check-square-o"></i></b>
</span>
<input type="text" name="site_logo_text" class="form-control" value="<?= $site_logo_text; ?>">
</div>
</div>
</div>
<!--- form-group row Ends --->


<div class="form-group row site_logo_image">
<!--- form-group row Starts --->
<label class="col-md-3 control-label"> Site Logo Image : </label>
<div class="col-md-6">
<div class="input-group">
<span class="input-group-addon">
<b><i class="fa fa-paper-plane"></i></b>
</span>
<input type="file" name="site_logo_image" class="form-control">
</div>
<img style="margin-top:7px;" src="<?= $site_logo_image; ?>" width="90" height="30">
</div>
</div>
<!--- form-group row Ends --->



<div class="form-group row">
<!--- form-group row Starts --->
<label class="col-md-3 control-label"> Enable Mobile Logo : </label>
<div class="col-md-6">
<select name="enable_mobile_logo" class="form-control" required="">
  <option value="1"> Yes </option>
  <option value="0" <?= ($enable_mobile_logo == 0)?"selected":""; ?>> No </option>
</select>
</div>
</div>
<!--- form-group row Ends --->


<div class="form-group row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Site Mobile Logo : </label>
<div class="col-md-6">
<div class="input-group">
<span class="input-group-addon">
<b><i class="fa fa-paper-plane"></i></b>
</span>
<input type="file" name="site_mobile_logo" class="form-control">
</div>
<img class="mt-1" src="<?= $site_mobile_logo; ?>" width="25" height="25">
<br>
</div>
</div><!--- form-group row Ends --->


<div class="form-group row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Site Email Logo : </label>
<div class="col-md-6">
<div class="input-group">
<span class="input-group-addon">
<b><i class="fa fa-paper-plane"></i></b>
</span>
<input type="file" name="site_logo" class="form-control">
</div>
<img class="mt-1" src="<?= $site_logo; ?>" width="110" height="40">
</div>
</div><!--- form-group row Ends --->

<div class="form-group row"><!--- form-group row Starts --->
  <label class="col-md-3 control-label"> Site Watermark Image : </label>
  <div class="col-md-6">
    <div class="input-group">
      <span class="input-group-addon">
        <b><i class="fa fa-paper-plane"></i></b>
      </span>
      <input type="file" name="site_watermark" class="form-control">
    </div>
    <img class="mt-1" src="../images/<?= $s_watermark; ?>" width="110" height="40">
  </div>
</div>
<div class="form-group row">
<!--- form-group row Starts --->
<label class="col-md-3 control-label"></label>
<div class="col-md-6">
<input type="submit" name="logo_update" class="form-control btn btn-success" value="Update Logo Settings">
</div>
</div>
<!--- form-group row Ends --->
					<!--- table-responsive Ends -->
				</div><!-- card-body Ends -->
			</div><!-- card card-default Ends -->
		</div><!-- col-lg-12 Ends -->
	</div><!-- row Ends -->
</div>
<!--- container Ends --->
<script>
<?php if($site_logo_type == "text"){ ?>
	$('.site_logo_image').hide();
	<?php }else{ ?>
	$('.site_logo_text').hide();
	<?php } ?>
	$(".site_logo_type").change(function(){
		var value = $(this).val();
		if(value == "text"){
			$('.site_logo_image').hide();
			$('.site_logo_text').show();
		}else if(value == "image"){
			$('.site_logo_text').hide();
			$('.site_logo_image').show();
		}
	});
</script>
<?php

if(isset($_POST['logo_update'])){
	$enable_mobile_logo = $input->post('enable_mobile_logo');
  $site_logo_type = $input->post('site_logo_type');
	$site_logo_text = $input->post('site_logo_text');
	

  $site_favicon = $_FILES['site_favicon']['name'];
	$site_favicon_tmp = $_FILES['site_favicon']['tmp_name'];
	
  $site_logo = $_FILES['site_logo']['name'];
	$site_logo_tmp = $_FILES['site_logo']['tmp_name']; 

  $site_mobile_logo = $_FILES['site_mobile_logo']['name'];
  $site_mobile_logo_tmp = $_FILES['site_mobile_logo']['tmp_name'];
	
  $site_logo_image = $_FILES['site_logo_image']['name'];
	$site_logo_image_tmp = $_FILES['site_logo_image']['tmp_name'];

  $site_watermark = $_FILES['site_watermark']['name'];
  $site_watermark_tmp = $_FILES['site_watermark']['tmp_name'];

	$favicon_extension = pathinfo($site_favicon, PATHINFO_EXTENSION);
	$logo_extension = pathinfo($site_logo, PATHINFO_EXTENSION);
	$logo_image_extension = pathinfo($site_logo_image, PATHINFO_EXTENSION);
	$allowed = array('jpeg','jpg','gif','png','tif','ico','webp');

	if(!in_array($favicon_extension,$allowed) & !empty($site_favicon) or !in_array($logo_extension,$allowed) & !empty($site_logo) or !in_array($logo_image_extension,$allowed) & !empty($site_logo_image)){
		echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
	}else{

    if(empty($site_favicon)){
      $site_favicon = $s_favicon;
      $site_favicon_s3 = $s_favicon_s3;
    }else{
      uploadToS3("images/$site_favicon",$site_favicon_tmp);
      $site_favicon_s3 = $enable_s3;
    }

    if(empty($site_logo)){
      $site_logo = $s_logo;
      $site_logo_s3 = $s_logo_s3;
    }else{
      uploadToS3("images/$site_logo",$site_logo_tmp);
      $site_logo_s3 = $enable_s3;
    }

    if(empty($site_mobile_logo)){
      $site_mobile_logo = $s_mobile_logo;
      $site_mobile_logo_s3 = $s_mobile_logo_s3;
    }else{
      uploadToS3("images/$site_mobile_logo",$site_mobile_logo_tmp);
      $site_mobile_logo_s3 = $enable_s3;
    }

    if(empty($site_logo_image)){
      $site_logo_image = $s_logo_image;
      $site_logo_image_s3 = $s_logo_image_s3;
    }else{
      uploadToS3("images/$site_logo_image",$site_logo_image_tmp);
      $site_logo_image_s3 = $enable_s3;
    }

    if(empty($site_watermark)){
      $site_watermark = $s_watermark;
    }else{
      move_uploaded_file($site_watermark_tmp, "../images/$site_watermark");
    }

		$logo_settings = $db->update("general_settings",array(
      
      "site_favicon" => $site_favicon,
      "site_logo_type" => $site_logo_type,
      "site_logo_text" => $site_logo_text,
      "site_logo_image" => $site_logo_image,
      "enable_mobile_logo"=>$enable_mobile_logo,
      "site_mobile_logo"=>$site_mobile_logo,
      "site_logo" => $site_logo,
      "site_watermark" => $site_watermark
      
    ));

		if($logo_settings){
			$insert_log = $db->insert_log($admin_id,"general_settings","","updated");
			
				echo "<script>alert_success('Logo Settings has been updated successfully.','index?logo_settings');</script>";
		  
    }

	}
}
?>
<?php } ?>