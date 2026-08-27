<?php
session_start();
include("includes/db.php");
require_once("../functions/email.php");

if(isset($_SESSION['admin_email'])){
echo "<script>window.open('index?dashboard','_self');</script>";
}
?>
<!doctype html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $site_name; ?> Admin - Forgot Password</title>
    <meta name="description" content="<?= $site_name; ?> admin, Reset your password.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-icon.png">
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/costum.css">
	<script src="vendors/scripts/jquery.js"></script>
    <script src="vendors/scripts/popper.js"></script>
	<script type="text/javascript" src="vendors/scripts/sweat_alert.js"></script>
</head>
<body class="bg-dark">
<div class="sufee-login d-flex align-content-center flex-wrap">
    <div class="container">
        <div class="login-content">
            <div class="login-logo pb-4">
                <a href="<?= $site_url; ?>/admin/login.php">
                    <h2 class="text-white"> <?= $site_name; ?> <span class="badge badge-success p-2 font-weight-bold">ADMIN</span></h2>
                </a>
			<p class="lead text-white" style=" margin-top:15px; margin-bottom:-18px;">
				Enter your email to receive a password reset link.
				</p>
            </div>
            <div class="login-form">
                <form method="post">
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" name="forgot_email" class="form-control" placeholder="Email">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success btn-flat m-b-15">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/main.js"></script>
<?php
if(isset($_POST['submit'])){
	$forgot_email = $input->post('forgot_email');
	$select_admin = $db->select("admins",array("admin_email" => $forgot_email));
	$count_admin_email = $select_admin->rowCount();
	if($count_admin_email == 0){
		echo "<script>
	    swal({
	    type: 'warning',
	    text: 'Hmm! We don\'t seem to have this email in our system.',
	    })
	  </script>";
	}else{
		if(send_admin_forgot_password()){
			echo "
			<script>
			swal({
			type: 'success',
			text: 'An email has been sent to your email address with instructions on how to change your password.',
			});
			</script>";
		}else{
			echo "
			<script>
			swal({
			type: 'warning',
			text: 'sorry,email could not be sent.',
			});
			</script>";
		}
	}
}
?>
</body>
</html>
