<?php
session_start();
require_once("includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_name = $row_login_seller->seller_name;
$login_seller_email = $row_login_seller->seller_email;

$form_errors = Flash::render("widerruf_errors");
$form_data = Flash::render("widerruf_data");

if(isset($_POST['submit'])){

	$rules = array(
		"full_name" => "required",
		"address" => "required",
		"email" => "email|required"
	);

	$messages = array(
		"full_name" => $lang['validation']['full_name_required2'],
		"address" => $lang['widerruf']['address_required'],
		"email" => $lang['validation']['email_required']
	);

	$val = new Validator($_POST,$rules,$messages);

	if($val->run() == false){
		Flash::add("widerruf_errors",$val->get_all_errors());
		Flash::add("widerruf_data",$_POST);
		echo "<script>window.open('widerruf','_self');</script>";
	}else{

		$full_name = strip_tags($input->post('full_name'));
		$address = strip_tags($input->post('address'));
		$email = strip_tags($input->post('email'));
		$order_number = strip_tags($input->post('order_number'));
		$ordered_date = strip_tags($input->post('ordered_date'));
		$received_date = strip_tags($input->post('received_date'));
		$reason = strip_tags($input->post('reason'));
		$date = date("F d, Y");

		$insert_withdrawal = $db->insert("withdrawal_notices",array(
			"seller_id" => $login_seller_id,
			"order_number" => $order_number,
			"full_name" => $full_name,
			"address" => $address,
			"email" => $email,
			"ordered_date" => $ordered_date,
			"received_date" => $received_date,
			"reason" => $reason,
			"status" => 'new',
			"date" => $date
		));

		if($insert_withdrawal){

			$withdrawal_id = $db->lastInsertId();

			$insert_notification = $db->insert("admin_notifications",array("seller_id" => $login_seller_id,"content_id" => $withdrawal_id,"reason" => "refund_request","date" => $date,"status" => "unread"));

			$data = [];
			$data['template'] = "dusupay_order";
			$data['to'] = $email;
			$data['subject'] = "$site_name: ".$lang['widerruf']['confirmation_email_subject'];
			$data['user_name'] = $full_name;
			$data['message'] = $lang['widerruf']['confirmation_email_message'];
			send_mail($data);

			Flash::add("widerruf_success",1);
			echo "<script>window.open('widerruf','_self');</script>";
		}

	}

}

$widerruf_success = Flash::render("widerruf_success");

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
	<title><?= $site_name; ?> - <?= $lang['widerruf']['page_title']; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">
	<link href="styles/bootstrap.css" rel="stylesheet">
	<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
	<link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="styles/styles.css" rel="stylesheet">
	<link href="styles/user_nav_styles.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<?php if(!empty($site_favicon)){ ?>
	<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>
</head>
<body class="is-responsive">
<?php require_once("includes/user_header.php"); ?>

<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-md-8 offset-md-2">

			<h2 class="mb-3"><?= $lang['widerruf']['page_title']; ?></h2>

			<?php if(!empty($widerruf_success)){ ?>
			<div class="alert alert-success">
				<?= $lang['widerruf']['submission_success']; ?>
			</div>
			<?php } ?>

			<?php if(is_array($form_errors)){ ?>
			<div class="alert alert-danger">
				<ul class="list-unstyled mb-0">
					<?php foreach($form_errors as $error){ ?>
					<li class="list-unstyled-item"><?= ucfirst($error); ?></li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>

			<div class="card mb-4">
				<div class="card-body">
					<p><?= $lang['widerruf']['intro']; ?></p>
					<p class="text-muted small"><?= $lang['widerruf']['intro_note']; ?></p>
				</div>
			</div>

			<div class="card">
				<div class="card-header">
					<?= $lang['widerruf']['form_heading']; ?>
				</div>
				<div class="card-body">

					<p class="text-muted"><?= $lang['widerruf']['recipient_prefix']; ?> <strong><?= $site_name; ?></strong>, [<?= $lang['widerruf']['address_placeholder']; ?>], <?= $site_email_address; ?></p>

					<p><?= $lang['widerruf']['declaration_text']; ?></p>

					<form method="post">

						<div class="form-group row">
							<label class="col-md-4 col-form-label"><?= $lang['widerruf']['order_number']; ?></label>
							<div class="col-md-8">
								<input type="text" name="order_number" class="form-control" value="<?= @$form_data['order_number']; ?>" placeholder="<?= $lang['widerruf']['order_number_placeholder']; ?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 col-form-label"><?= $lang['widerruf']['ordered_date']; ?></label>
							<div class="col-md-8">
								<input type="date" name="ordered_date" class="form-control" value="<?= @$form_data['ordered_date']; ?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 col-form-label"><?= $lang['widerruf']['received_date']; ?></label>
							<div class="col-md-8">
								<input type="date" name="received_date" class="form-control" value="<?= @$form_data['received_date']; ?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 col-form-label"><?= $lang['label']['full_name']; ?></label>
							<div class="col-md-8">
								<input type="text" name="full_name" class="form-control" value="<?= @$form_data['full_name'] ?: $login_seller_name; ?>" required>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 col-form-label"><?= $lang['widerruf']['address']; ?></label>
							<div class="col-md-8">
								<textarea name="address" class="form-control" rows="3" required><?= @$form_data['address']; ?></textarea>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 col-form-label"><?= $lang['label']['email']; ?></label>
							<div class="col-md-8">
								<input type="email" name="email" class="form-control" value="<?= @$form_data['email'] ?: $login_seller_email; ?>" required>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 col-form-label"><?= $lang['widerruf']['reason_optional']; ?></label>
							<div class="col-md-8">
								<textarea name="reason" class="form-control" rows="3"><?= @$form_data['reason']; ?></textarea>
							</div>
						</div>

						<div class="form-group row">
							<div class="col-md-8 offset-md-4">
								<button type="submit" name="submit" class="btn btn-success"><?= $lang['widerruf']['submit_button']; ?></button>
							</div>
						</div>

					</form>

				</div>
			</div>

		</div>
	</div>
</div>

<?php require_once("includes/footer.php"); ?>
</body>
</html>
