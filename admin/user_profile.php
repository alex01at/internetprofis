<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
} else {
  if(isset($_GET['user_profile'])){
     $edit_id = $input->get('user_profile');
     $edit_admin = $db->select("admins",array('admin_id'=>$edit_id));
     $row_edit = $edit_admin->fetch();
     $a_id = $row_edit->admin_id;
     $a_name = $row_edit->admin_name;
     $a_email = $row_edit->admin_email;
     $a_pass = $row_edit->admin_pass;
     $a_image = $row_edit->admin_image;
     $showImage = $row_edit->admin_image;
     $a_country = $row_edit->admin_country;
     $a_job = $row_edit->admin_job;
     $a_contact = $row_edit->admin_contact;
     $a_about = $row_edit->admin_about;
     $isS3 = $row_edit->isS3;
   }
   $sessionTimeoutInMinutes = $login_time / 60;
   $sessionTimeout = intval($sessionTimeoutInMinutes);
?>
<div class="main-container">
	<div class="pd-ltr-20 xs-pd-20-10">
		<div class="min-height-200px">
			<div class="row">
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
					<div class="pd-20 card-box">
						<div class="profile-photo">
							<img src="<?= getImageUrl("admins",$showImage); ?>" class="avatar-photo">
						</div>
						<h5 class="text-center h5 mt-1 pt-1"><?= $a_name; ?></h5>
						<div class="profile-info">
							<h5 class="mb-20 h5 text-blue">Contact Information</h5>
							<ul>
								<li>
									<span>Email Address:</span>
									<?= $a_email ?>
								</li>
								<li>
									<span>Country:</span>
									<?= $a_country ?>
								</li>
								<li>
									<span>Admin Job</span>
									<?= $a_job ?>
								</li>
								<li>
									<span>About:</span>
									<?= $a_about ?>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
					<div class="card-box height-100-p overflow-hidden">
						<div class="profile-tab height-100-p">
							<div class="tab height-100-p">
								<div class="profile-setting">
								<form action="" method="post" enctype="multipart/form-data">
										<ul class="profile-edit-list row">
											<li class="weight-500 col-md-10">
												<h4 class="text-blue h5 mb-20">Edit Your Personal Setting</h4>
												<div class="form-group">
													<label>Full Name</label>
													<input type="text" name="admin_name" class="form-control" required
														value="<?= $a_name; ?>">
												</div>
												<div class="form-group">
													<label>Email</label>
													<input type="email" name="admin_email" class="form-control" required
														value="<?= $a_email; ?>">
												</div>
												<div class="form-group">
													<label>Country</label>
													<input type="text" name="admin_country" class="form-control"
														required value="<?= $a_country; ?>">
												</div>
												<div class="form-group">
													<label>Contact</label>
													<input type="text" name="admin_countact" class="form-control"
														 value="<?= $a_contact; ?>">
												</div>
												<div class="form-group">
													<label>Job</label>
													<input type="text" name="admin_job" class="form-control" required
														value="<?= $a_job; ?>">
												</div>
												<div class="form-group">
													<label>About you</label>
													<textarea name="admin_about" class="form-control"
														rows="3"><?= $a_about; ?></textarea>
												</div>
												<div class="form-group">
													<label>Admin Image</label>
													<input type="file" name="admin_image" class="form-control">
													<br>
													<img src="<?= getImageUrl("admins",$showImage); ?>" width="80px"
														height="80px">
												</div>
												<div class="form-group">
													<label>Login Time</label>
													<input type="number" name="login_time" class="form-control" value="<?= $login_time; ?>">Stay login for <?=$sessionTimeout?> Minutes 
												</div>
												<hr class="card-hr" />
												<h4 class="h3 mb-3">
													Change Account Password
													<span class="h6 text-muted mb-3">
														<br> If you do not wish to change your password, then just leave
														the fields below empty.
													</span>
												</h4>
												<div class="form-group row">
													<!--- form-group row Starts --->
													<label class="col-md-3 control-label"> Admin Password : </label><br>
													<div class="password-strength-checker">
														<div class="input-group">
															<!--- input-group Starts --->
															<span class="input-group-addon">
																<i class="fa fa-check tick1 text-success"></i>
																<i class="fa fa-times cross1 text-danger"></i>
															</span>
															<input type="password" name="admin_pass" id="password"
																class="form-control"><br>
															<span class="input-group-addon">
																<div id="meter_wrapper">
																	<span id="pass_type"></span>
																	<div id="meter"></div>
																</div>
															</span>
														</div>
														<!--- input-group Ends --->
													</div>
												</div>
												<!--- form-group row Ends --->
												<div class="form-group row">
													<!--- form-group row Starts --->
													<label class="col-md-3 control-label"> Confirm Password :
													</label>
													<div class="col-md-6">
														<div class="input-group">
															<!--- input-group Starts --->
															<span class="input-group-addon">
																<i class="fa fa-check tick2 text-success"></i>
																<i class="fa fa-times cross2 text-danger"></i>
															</span>
															<input type="password" name="confirm_admin_pass"
																id="confirm_password" class="form-control">
														</div>
														<!--- input-group Ends --->
													</div>
												</div>
<div class="form-group mb-0">
									<input type="submit" name="update" class="btn btn-success form-control"
										value="Update User Profile">
								</div>
								</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- js -->
<script src="vendors/scripts/core.js"></script>
<script src="vendors/scripts/script.min.js"></script>
<script src="vendors/scripts/process.js"></script>
<script src="vendors/scripts/layout-settings.js"></script>
<script src="src/plugins/cropperjs/dist/cropper.js"></script>
<script>
	window.addEventListener('DOMContentLoaded', function () {
		var image = document.getElementById('image');
		var cropBoxData;
		var canvasData;
		var cropper;
		$('#modal').on('shown.bs.modal', function () {
			cropper = new Cropper(image, {
				autoCropArea: 0.5,
				dragMode: 'move',
				aspectRatio: 3 / 3,
				restore: false,
				guides: false,
				center: false,
				highlight: false,
				cropBoxMovable: false,
				cropBoxResizable: false,
				toggleDragModeOnDblclick: false,
				ready: function () {
					cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
				}
			});
		}).on('hidden.bs.modal', function () {
			cropBoxData = cropper.getCropBoxData();
			canvasData = cropper.getCanvasData();
			cropper.destroy();
		});
	});
</script>
<script>
	$(document).ready(function () {
		$("#password").keyup(function () {
			check_pass();
		});
	});
	function check_pass() {
		var val = document.getElementById("password").value;
		var meter = document.getElementById("meter");
		var no = 0;
		if (val != "") {
			// If the password length is less than or equal to 6
			if (val.length <= 6) no = 1;
			// If the password length is greater than 6 and contain any lowercase alphabet or any number or any special character
			if (val.length > 6 && (val.match(/[a-z]/) || val.match(/\d+/) || val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)))
				no = 2;
			// If the password length is greater than 6 and contain alphabet,number,special character respectively
			if (val.length > 6 && ((val.match(/[a-z]/) && val.match(/\d+/)) || (val.match(/\d+/) && val.match(
					/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) || (val.match(/[a-z]/) && val.match(
					/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)))) no = 3;
			// If the password length is greater than 6 and must contain alphabets,numbers and special characters
			if (val.length > 6 && val.match(/[a-z]/) && val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))
				no = 4;
			if (no == 1) {
				$("#meter").animate({
					width: '50px'
				}, 300);
				meter.style.backgroundColor = "red";
				document.getElementById("pass_type").innerHTML = "Very Weak";
			}
			if (no == 2) {
				$("#meter").animate({
					width: '100px'
				}, 300);
				meter.style.backgroundColor = "#F5BCA9";
				document.getElementById("pass_type").innerHTML = "Weak";
			}
			if (no == 3) {
				$("#meter").animate({
					width: '150px'
				}, 300);
				meter.style.backgroundColor = "#FF8000";
				document.getElementById("pass_type").innerHTML = "Good";
			}
			if (no == 4) {
				$("#meter").animate({
					width: '200px'
				}, 300);
				meter.style.backgroundColor = "#00FF40";
				document.getElementById("pass_type").innerHTML = "Strong";
			}
		} else {
			meter.style.backgroundColor = "";
			document.getElementById("pass_type").innerHTML = "";
		}
	}
	$(document).ready(function () {
		$('.tick1').hide();
		$('.cross1').hide();
		$('.tick2').hide();
		$('.cross2').hide();
		$('#confirm_password').focusout(function () {
			var password = $('#password').val();
			var confirmPassword = $('#confirm_password').val();
			if (password == confirmPassword) {
				$('.tick1').show();
				$('.cross1').hide();
				$('.tick2').show();
				$('.cross2').hide();
			} else {
				$('.tick1').hide();
				$('.cross1').show();
				$('.tick2').hide();
				$('.cross2').show();
			}
		});
	});
</script>
</body>
</html>
<?php


if(isset($_POST['update'])){
	
   $admin_name = $input->post('admin_name');
   $admin_email = $input->post('admin_email');
   $admin_country = $input->post('admin_country');
   $admin_contact = $input->post('admin_contact');
   $admin_job = $input->post('admin_job');
   $admin_about = $input->post('admin_about');
   $admin_pass = $input->post('admin_pass');
   $confirm_admin_pass = $input->post('confirm_admin_pass');
   $login_time = $input->post('login_time');
	
   $admin_image = $_FILES['admin_image']['name'];
   $tmp_admin_image = $_FILES['admin_image']['tmp_name'];
	
   if(empty($admin_pass) and empty($confirm_admin_pass)){
      $encrypt_password = $a_pass;
   }else{
      if($admin_pass !== $confirm_admin_pass){
      echo "<script>alert('Your Password Does Not Match, Please Try Again.');</script>";
      echo "<script>window.open('index?user_profile=$a_id','_self');</script>";
      }else{
         $encrypt_password = password_hash($admin_pass, PASSWORD_DEFAULT);
      }
   }

   $allowed = array('jpeg','jpg','gif','png','tif','ico','webp');
   $file_extension = pathinfo($admin_image, PATHINFO_EXTENSION);

   if(!in_array($file_extension,$allowed) & !empty($admin_image)){
      echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
   }else{

      if(empty($admin_image)){
         $admin_image = $a_image;
         $isS3 = $isS3;
      }else{
         uploadToS3("admin_images/$admin_image",$tmp_admin_image);
         $isS3 = $enable_s3;
      }

      $update_admin = $db->update("admins",["admin_name"=>$admin_name,"admin_email"=>$admin_email,"admin_pass"=>$encrypt_password,"admin_image"=>$admin_image,"admin_contact"=>$admin_contact,"admin_country"=>$admin_country,"admin_job"=>$admin_job,"admin_about"=>$admin_about,"login_time"=>$login_time],["admin_id"=>$a_id]);

      if($update_admin){
         echo "
         <script>
            alert_success('Your User Profile Has Been Updated Successfully,So Please Login Again.','logout');
         </script>";
      }

   }
	
}

?>

</div>

<?php } ?>