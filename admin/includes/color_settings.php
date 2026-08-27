<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('../../login','_self');</script>";
}else{
	$get_general_settings = $db->select("general_settings");   
	$row_general_settings = $get_general_settings->fetch();
	$site_color = $row_general_settings->site_color;
	$footer_color = $row_general_settings->footer_color;
	$header_color = $row_general_settings->header_color;
$site_hover_color = $row_general_settings->site_hover_color;
$site_border_color = $row_general_settings->site_border_color;
$post_footer_color = $row_general_settings->post_footer_color;
$navbar_color = $row_general_settings->navbar_color;
require 'updateHtaccess.php';
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
					
					<h4 class="h3">Color Settings</h4>
				</div><!-- card-header Ends -->
				<div class="card-body">
					<!-- card-body Starts -->
					
					<form method="post" enctype="multipart/form-data">
<div class="row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Site Color : </label>
<div class="col-md-6">
<input type="color" name="site_color" class="form-control p-0 pl-1 pr-1" value="<?= $site_color; ?>">
</div>
</div><!--- form-group row Ends --->

<hr>
<div class="row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Site Hover Color : </label>
<div class="col-md-6">
<input type="color" name="site_hover_color" class="form-control p-0 pl-1 pr-1" value="<?= $site_hover_color; ?>">
</div>
</div><!--- form-group row Ends --->
<hr>

<div class="row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Site Border Color : </label>
<div class="col-md-6">
<input type="color" name="site_border_color" class="form-control p-0 pl-1 pr-1" value="<?= $site_border_color; ?>">
</div>
</div><!--- form-group row Ends --->
<hr>
<div class="row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Footer Color : </label>
<div class="col-md-6">
<input type="color" name="footer_color" class="form-control p-0 pl-1 pr-1" value="<?= $footer_color; ?>">
</div>
</div>
<hr>
<div class="row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Header Color : </label>
<div class="col-md-6">
<input type="color" name="header_color" class="form-control p-0 pl-1 pr-1" value="<?= $header_color; ?>">
</div>
</div>
<hr>
<div class="row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Navbar Color : </label>
<div class="col-md-6">
<input type="color" name="navbar_color" class="form-control p-0 pl-1 pr-1" value="<?= $navbar_color; ?>">
</div>
</div>
<hr>
<div class="row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Footer Credit Color : </label>
<div class="col-md-6">
<input type="color" name="post_footer_color" class="form-control p-0 pl-1 pr-1" value="<?= $post_footer_color; ?>">
</div>
</div>
<hr>
<div class="row">
<!--- form-group row Starts --->
<label class="col-md-3 control-label"></label>
<div class="col-md-6">
<input type="submit" name="color_settings_update" class="form-control btn btn-success" value="Update Color Settings">
</div>
</div>
<!--- form-group row Ends --->


</form>
					<!--- table-responsive Ends -->
				</div><!-- card-body Ends -->
			</div><!-- card card-default Ends -->
		</div><!-- col-lg-12 Ends -->
	</div><!-- row Ends -->
</div>
<!--- container Ends --->
<?php
if(isset($_POST['color_settings_update'])){
	$site_color = $input->post('site_color');
  $site_hover_color = $input->post('site_hover_color');
  $site_border_color = $input->post('site_border_color');
  $footer_color = $input->post('footer_color');
  $post_footer_color = $input->post('post_footer_color');
  $header_color = $input->post('header_color');
  $navbar_color = $input->post('navbar_color');

  $update_color_settings = $db->update("general_settings",array(
	"site_color"=>$site_color,
      "site_hover_color"=>$site_hover_color,
      "footer_color"=>$footer_color,
      "post_footer_color"=>$post_footer_color,
      "header_color"=>$header_color,
      "navbar_color"=>$navbar_color,
      "site_border_color"=>$site_border_color
	));
	if($update_color_settings){
		$insert_log = $db->insert_log($admin_id,"general_settings","","updated");
		
			echo "<script>alert_success('Color Settings has been updated successfully.','index?color_settings');</script>";
	  
}

}

?>
<?php } ?>