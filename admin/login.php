<?php

session_start();

include("includes/db.php");

if(isset($_SESSION['admin_email'])){	
  echo "<script>window.open('index?dashboard','_self');</script>";
}

$_SESSION['adminLanguage'] = 1;

?>

<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title><?= $site_name; ?> Admin Login</title>

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
							<h2 class="text-center text-primary">Login Admin</h2>
						</div>
						<?php if(isset($_GET['session_expired'])){ ?>
            
            <div class="alert alert-danger mb-3 alert-dismissible fade show">

                    <button type="button" class="close" data-dismiss="alert">

                    <span>&times;</span>

                    </button>

                    <span class=" mt-3"><i class="fa  fa-1x fa-exclamation-circle"></i> Your session has expired. Please login again!</span>
                    
              </div>
            
            <?php } ?>
			<form action="" id="myform" method="post" autocomplete="off">
							
							<div class="input-group custom">
								<input type="text" class="form-control form-control-lg" value="<?php if(isset($_SESSION["r_email"])) { echo $_SESSION["r_email"]; } ?>" placeholder="Email" name="admin_email">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>
							<div class="input-group custom">
    <input type="password" id="password" class="form-control form-control-lg" placeholder="Password" name="admin_pass" value="<?php if(isset($_SESSION["r_password"])) { echo $_SESSION["r_password"]; } ?>">
    <div class="input-group-append custom">
        <!-- Das Auge direkt als klickbares Element vor das Schloss-Icon -->
        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">👁️</span>
        <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
    </div>
</div>
							<div class="row pb-30">
								<div class="col-6">
									<div class="custom-control custom-checkbox">
									<!--<label>

<input type="checkbox" <?php if(isset($_SESSION["r_email"])){ ?> checked="checked" <?php } ?> name="remember"> Remember Me

</label> --!>
									</div>
								</div>
								<div class="col-6">
									<div class="forgot-password"><a href="forgot-password">Forgot Password</a></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										
										<button type="submit" class="btn btn-primary btn-lg btn-block" name="admin_login">Sign In</button>
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
	<!-- <script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script> -->
	<script>

    $(document).ready(function(){
     
    <?php if(!isset($_SESSION["r_email"])){ ?>

    setTimeout(function(){
    	
    document.getElementById("myform").reset();

    $(".pass").val("");

    },100);
    	  
    <?php } ?>
    	  
    });
		
	const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');

togglePassword.addEventListener('click', function (e) {
    // Typ abfragen
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    
    // Typ umschalten
    password.setAttribute('type', type);
    
    // Optional: Icon ändern (z.B. durchgestrichenes Auge)
    this.textContent = type === 'password' ? '👁️' : '🙈';
});
    </script>
</body>
</html>
<?php

if(isset($_POST['admin_login'])){
	
  $admin_email = $input->post('admin_email');
  $admin_pass = $input->post('admin_pass');
  	
  $select_admins = $db->query("select * from admins where admin_email=:a_email OR admin_user_name=:a_user_name",array("a_email"=>$admin_email,"a_user_name"=>$admin_email));
  $count_admins = $select_admins->rowCount();

  if($count_admins != 0){
    $row_admins = $select_admins->fetch();
    $hash_password = $row_admins->admin_pass;
    $decrypt_password = password_verify($admin_pass, $hash_password);
  }else{
    $decrypt_password = 0;
  }
  	
  if($decrypt_password == 0){
  
    echo "<script>
      swal({
         type: 'warning',
         text: 'Opps! password or username is incorrect. Please try again.',
      });
    </script>";

  }else{
  		
    // $get_admin = $db->select("admins",array("admin_email"=>$admin_email,"admin_pass"=>$hash_password));
$get_admin = $db->query("select * from admins where (admin_email=:a_email OR admin_user_name=:a_user_name) AND admin_pass=:a_pass", array("a_email"=>$admin_email,"a_user_name"=>$admin_email,"a_pass"=>$hash_password));
    if($get_admin->rowCount() == 1){
    	
      if(!empty($_POST["remember"])){  
        $_SESSION["r_email"] = $admin_email;
        $_SESSION["r_password"] = $admin_pass;
      }else{
        if(isset($_SESSION["r_email"])){ 
          unset($_SESSION["r_email"]); 
        }

        if(isset($_SESSION["r_password"])){  
          unset($_SESSION["r_password"]);  
    	  }
      }
    	
      $_SESSION['admin_email'] = $admin_email;
      $_SESSION['loggedin_time'] = time();
          
      echo "<script>
          swal({
          type: 'success',
          text: 'Successfully Logging you in...',
          timer: 1000,
          onOpen: function(){
          swal.showLoading()
          }
          }).then(function(){
            window.open('index?dashboard','_self');
          });
        </script>";
                
    }
  	
  }
	
}

?>
