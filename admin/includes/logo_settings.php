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
$s_favicon_s3 = $row_general_settings->site_favicon_s3;
$s_logo_image_s3 = $row_general_settings->site_logo_image_s3;
$s_mobile_logo_s3 = $row_general_settings->site_mobile_logo_s3;
$s_logo_s3 = $row_general_settings->site_logo_s3;


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
<input type="hidden" name="site_favicon" id="picker_site_favicon" value="<?= htmlspecialchars($s_favicon); ?>">
</div>
<div class="mb-2"><img class="mt-1" id="preview_site_favicon" src="<?= $site_favicon; ?>" width="30" height="30"></div>
<button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('site_favicon','general_settings')">Choose Image</button>
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
<input type="hidden" name="site_logo_image" id="picker_site_logo_image" value="<?= htmlspecialchars($s_logo_image); ?>">
</div>
<div class="mb-2"><img style="margin-top:7px;" id="preview_site_logo_image" src="<?= $site_logo_image; ?>" width="90" height="30"></div>
<button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('site_logo_image','general_settings')">Choose Image</button>
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
<input type="hidden" name="site_mobile_logo" id="picker_site_mobile_logo" value="<?= htmlspecialchars($s_mobile_logo); ?>">
</div>
<div class="mb-2"><img class="mt-1" id="preview_site_mobile_logo" src="<?= $site_mobile_logo; ?>" width="25" height="25"></div>
<button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('site_mobile_logo','general_settings')">Choose Image</button>
</div>
</div><!--- form-group row Ends --->


<div class="form-group row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Site Email Logo : </label>
<div class="col-md-6">
<div class="input-group">
<span class="input-group-addon">
<b><i class="fa fa-paper-plane"></i></b>
</span>
<input type="hidden" name="site_logo" id="picker_site_logo" value="<?= htmlspecialchars($s_logo); ?>">
</div>
<div class="mb-2"><img class="mt-1" id="preview_site_logo" src="<?= $site_logo; ?>" width="110" height="40"></div>
<button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('site_logo','general_settings')">Choose Image</button>
</div>
</div><!--- form-group row Ends --->

<div class="form-group row"><!--- form-group row Starts --->
  <label class="col-md-3 control-label"> Site Watermark Image : </label>
  <div class="col-md-6">
    <div class="input-group">
      <span class="input-group-addon">
        <b><i class="fa fa-paper-plane"></i></b>
      </span>
      <input type="hidden" name="site_watermark" id="picker_site_watermark" value="<?= htmlspecialchars($s_watermark); ?>">
    </div>
    <div class="mb-2"><img class="mt-1" id="preview_site_watermark" src="../images/<?= $s_watermark; ?>" width="110" height="40"></div>
    <button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('site_watermark','general_settings')">Choose Image</button>
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

  $site_favicon = $input->post('site_favicon'); // already uploaded by the image picker, or unchanged
  $site_logo = $input->post('site_logo');
  $site_mobile_logo = $input->post('site_mobile_logo');
  $site_logo_image = $input->post('site_logo_image');
  $site_watermark = $input->post('site_watermark');

  $site_favicon_s3 = ($site_favicon == $s_favicon) ? $s_favicon_s3 : $enable_s3;
  $site_logo_s3 = ($site_logo == $s_logo) ? $s_logo_s3 : $enable_s3;
  $site_mobile_logo_s3 = ($site_mobile_logo == $s_mobile_logo) ? $s_mobile_logo_s3 : $enable_s3;
  $site_logo_image_s3 = ($site_logo_image == $s_logo_image) ? $s_logo_image_s3 : $enable_s3;

		$logo_settings = $db->update("general_settings",array(

      "site_favicon" => $site_favicon,
      "site_favicon_s3" => $site_favicon_s3,
      "site_logo_type" => $site_logo_type,
      "site_logo_text" => $site_logo_text,
      "site_logo_image" => $site_logo_image,
      "site_logo_image_s3" => $site_logo_image_s3,
      "enable_mobile_logo"=>$enable_mobile_logo,
      "site_mobile_logo"=>$site_mobile_logo,
      "site_mobile_logo_s3" => $site_mobile_logo_s3,
      "site_logo" => $site_logo,
      "site_logo_s3" => $site_logo_s3,
      "site_watermark" => $site_watermark

    ));

		if($logo_settings){
			$insert_log = $db->insert_log($admin_id,"general_settings","","updated");

				echo "<script>alert_success('Logo Settings has been updated successfully.','index?logo_settings');</script>";

    }
}
?>
<?php } ?>