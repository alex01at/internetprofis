<?php

session_start();
include("includes/db.php");

if(isset($_SESSION['admin_email'])){
	echo "<script>window.open('index?dashboard','_self');</script>";
}

$code = $input->get('code');
$select_admin = $db->select("admins",array("admin_pass" => $code));
$count_admin = $select_admin->rowCount();

if($count_admin == 0){
	echo "
	<script>
	alert('Your password change link is invalid.');
	window.open('login','_self');
	</script>";
	exit();
}

$row_admin = $select_admin->fetch();
$admin_id = $row_admin->admin_id;
$admin_user_name = $row_admin->admin_user_name;

?>

<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title><?= $site_name; ?> Admin - Change Password</title>

	<!-- Site favicon -->
	<?php if(!empty($site_favicon)){ ?>
		<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<script type="text/javascript" src="vendors/scripts/sweat_alert.js"></script>
	<style>
		.swal2-popup .swal2-styled.swal2-confirm {
			background-color: #28a745 !important;
		}
		.log-width{
			width: 550px;
			margin: 0 auto;
		}
	</style>
</head>
<body class="login-page">
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
			<a href="login.php">
                       <h2 class="text-black"> <?= $site_name; ?>  <span class="badge badge-success p-2 font-weight-bold">ADMIN</span></h2>
                    </a>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="main-container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Change Password</h2>
							<p class="text-center">Dear <?= htmlspecialchars($admin_user_name, ENT_QUOTES, 'UTF-8'); ?>, you can change your password here.</p>
						</div>
						<form action="" id="myform" method="post" autocomplete="off">

							<div class="input-group custom">
								<input type="password" class="form-control form-control-lg" placeholder="New Password" name="new_pass">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							<div class="input-group custom">
								<input type="password" class="form-control form-control-lg" placeholder="New Password Again" name="new_pass_again">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<button type="submit" class="btn btn-primary btn-lg btn-block" name="submit">Change Password</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
</body>
</html>
<?php
if(isset($_POST['submit'])){
	$new_pass = $input->post('new_pass');
	$new_pass_again = $input->post('new_pass_again');
	if($new_pass != $new_pass_again){
		echo "
		<script>
		swal({
		  type: 'warning',
		  text: 'Your passwords don\'t match. Please try again.',
		});
		</script>";
	}elseif(strlen($new_pass) < 8){
		echo "
		<script>
		swal({
		  type: 'warning',
		  text: 'Please choose a password with at least 8 characters.',
		});
		</script>";
	}else{
		$encrypted_password = password_hash($new_pass, PASSWORD_DEFAULT);
		$update_password = $db->update("admins",array("admin_pass"=>$encrypted_password),array("admin_id"=>$admin_id));
		if($update_password){
			echo "
			<script>
				swal({
				  type: 'success',
				  text: 'Your password has been updated successfully. Redirecting you to login page...',
				  timer: 5000,
				  onOpen: function(){
				  swal.showLoading()
				  }
				  }).then(function(){
					window.open('login','_self');
				})
			</script>";
		}
	}
}
?>
