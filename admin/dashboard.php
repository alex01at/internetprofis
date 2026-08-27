<?php
$get_app = $db->select("app_info");
$row_app = $get_app->fetch();
$current_version = $row_app->version;
$r_date = $row_app->r_date;
?>
<div class="main-container">
	<div class="row">
<div class="pd-ltr-20">
  <div class="alert alert-success alert-dismissible fade show" role="alert" id="news">
    <?= !empty($news) ? htmlspecialchars($news, ENT_QUOTES, 'UTF-8') : 'no news' ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</div>
	</div>
	<div class="row">
		<div class="col-xl-4 mb-30">
			<div class="card-box widget-style1">
				<div class="card-header">*Important*</div>
				<div class="d-flex flex-wrap align-items-center">
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody>
								<tr>
									<td><?= $count_proposals; ?></td>
									<td>Proposals Pending Approval</td>
									<td><span><a href="index?view_proposals"
												class="btn btn-danger btn-sm btn-block">View
												Details</a></span></td>
								</tr>
								<tr <?php if ($count_requests > 0) { ?>class="blink" <?php } ?>>
									<td><?= $count_requests; ?></td>
									<td>Buyer Requests Awaiting Approval</td>
									<td><span><a href="index?buyer_requests"
												class="btn btn-danger btn-sm btn-block">View
												Requests</a></span></td>
								</tr>
								<tr <?php if ($count_referrals > 0) { ?>class="blink" <?php } ?>>
									<td><?= $count_referrals; ?></td>
									<td>Referrals Awaiting Approval</td>
									<td><span><a href="index?view_referrals" class="btn btn-danger btn-sm btn-block">View
												Details</a></span></td>
								</tr>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-4 mb-30">
		<div class="card mb-5"><!--- card mb-5 Starts --->
      <div class="card-header"><!--- card-header Starts --->
          <h4 class="h4 mb-0"><i class="fa fa-money fa-fw"></i> Application Information</h4>
      </div><!--- card-header Ends --->
      <div class="card-body p-0"><!--- card-body Starts --->
        <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-3"><!--- form-group row Starts --->
          <label class="col-md-5">Current Version : </label>
          <div class="col-md-7 text-right">
          <?= $current_version; ?>
          </div>
          </div><!--- form-group row Ends --->
          <hr class="mt-0 mb-0">
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-2"><!--- form-group row Starts --->
          <label class="col-md-5"> Realease Date : </label>
          <div class="col-md-7 text-right">
          <?= $r_date; ?>
          </div>
          </div><!--- form-group row Ends --->
          <hr class="mt-0 mb-0">
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-2"><!--- form-group row Starts --->
          <label class="col-md-4"> Php Version : </label>
          <div class="col-md-8 text-right">
          <?= phpversion(); ?>
          </div>
          </div><!--- form-group row Ends --->
          <hr class="mt-0 mb-0">
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-2"><!--- form-group row Starts --->
          <label class="col-md-4"> Installed On : </label>
          <div class="col-md-8 text-right">
          <a href="<?= $site_url; ?>" target="_blank" style="color: green;"><?= $site_url; ?></a>
          </div>
          </div><!--- form-group row Ends --->
        </form><!--- form Ends --->
      </div><!--- card-body Ends --->
    </div>
		</div>
		<div class="col-xl-4 mb-30">
			<?=$sponsord_ad ?>
		</div>
	</div>
	<div class="row">
	<div class="col-xl-3 col-lg-6 col-md-6 mb-30">
			<div class="card text-white border-warning mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
				<div class="card-header bg-warning">
					<div class="row">
						<div class="col-3">
							<i class="fa fa-file-text-o fa-5x"></i>
						</div>
						<div class="col-9 text-right">
						<div class="huge"><?= $count_notifications; ?> </div>
                        <div class="text-caption">Alerts</div>
						<?php if ($count_notifications > 0) { ?>
						<i class="fa fa-2x fa-exclamation-circle icon-alert"></i>
						<?php  } ?>
						</div>
					</div>
				</div>
				<a href="index?view_notifications">
					<div class="card-footer">
						<span class="float-left text-success">
							View Details
						</span>
						<span class="float-right text-success">
							<i class="fa fa-arrow-circle-o-right"></i>
						</span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 mb-30">
			<div class="card text-white border-success mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
				<div class="card-header bg-success">
					<div class="row">
						<div class="col-3">
							<i class="fa fa-users fa-5x"></i>
						</div>
						<div class="col-9 text-right">
							<div class="huge"><?= $count_sellers; ?></div>
							<div class="text-caption">Users</div>
						</div>
					</div>
				</div>
				<a href="index?view_sellers">
					<div class="card-footer">
						<span class="float-left text-success">
							View Details
						</span>
						<span class="float-right text-success">
							<i class="fa fa-arrow-circle-o-right"></i>
						</span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 mb-30">
			<div class="card text-white border-info mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
				<div class="card-header bg-info">
					<div class="row">
						<div class="col-3">
							<i class="fa fa-balance-scale fa-5x"></i>
						</div>
						<div class="col-9 text-right">
							<div class="huge"><?=$totalSale; ?></div> Total Sale
						</div>
					</div>
				</div>
				<a href="index?view_orders">
					<div class="card-footer">
						<span class="float-left text-success">
							View Orders
						</span>
						<span class="float-right text-success">
							<i class="fa fa-arrow-circle-o-right"></i>
						</span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div class="col-xl-3 mb-30 mb-30">
		<div class="card text-white border-danger mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             <div class="card-header bg-danger">
                 <div class="row">
                     <div class="col-3">
                        <i class="fa fa-phone-square fa-5x"></i>
                     </div>
                     <div class="col-9 text-right">
                        <div class="huge"><?= $count_support_tickets; ?> </div>
                        <div class="text-caption">Support Requests</div>
						<?php if ($count_support_tickets > 0) { ?>
						<i class="fa fa-2x fa-exclamation-circle icon-alert"></i>
						<?php  } ?>
                     </div>
                 </div>
             </div>
             <a href="index?view_support_requests">
                 <div class="card-footer">
                     <span class="float-left text-danger">View Details</span>
                     <span class="float-right text-danger">
                        <i class="fa fa-arrow-circle-o-right"></i>
                     </span>
                     <div class="clearfix"></div>
                 </div>
             </a>
        </div>    
		</div>
	</div>
	<div class="row">
	<div class="col-xl-3 mb-30 mb-30">
		<div class="card text-white border-secundary mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             <div class="card-header bg-secundary">
                 <div class="row">
                     
                     <div class="col-12 text-center">
                        <div class="huge"><h2><?php
                $count_orders = $db->count("orders");
            ?>
							<?= $count_orders; ?> </h2></div>
                        <span class="h4">Active Orders</span>
						
                     </div>
                 </div>
             </div>
             
        </div>    


	</div>
	<div class="col-xl-3 mb-30 mb-30">
		<div class="card text-white border-secundary mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             <div class="card-header bg-secundary">
                 <div class="row">
                     
                     <div class="col-12 text-center">
                        <div class="huge"><h2><?php
                $count_orders = $db->count("orders",array("order_status" => "completed"));
                ?>
							<?= $count_orders; ?> </h2></div>
                        <span class="h4">Completed Orders</span>
						
                     </div>
                 </div>
             </div>
             
        </div>    


	</div>
	<div class="col-xl-3 mb-30 mb-30">
		<div class="card text-white border-secundary mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             <div class="card-header bg-secundary">
                 <div class="row">
                     
                     <div class="col-12 text-center">
                        <div class="huge"><h2><?php
                $count_orders = $db->count("orders",array("order_status" => "delivered"));
                ?>
							<?= $count_orders; ?> </h2></div>
                        <span class="h4">Delivered Orders</span>
						
                     </div>
                 </div>
             </div>
             
        </div>    


	</div>
	<div class="col-xl-3 mb-30 mb-30">
		<div class="card text-white border-secundary mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             <div class="card-header bg-secundary">
                 <div class="row">
                     
                     <div class="col-12 text-center">
                        <div class="huge"><h2><?php
                $count_orders = $db->count("orders",array("order_status" => "cancelled"));
                ?>
							<?= $count_orders; ?> </h2></div>
                        <span class="h4">Cancelled Orders</span>
						
                     </div>
                 </div>
             </div>
             
        </div>    


	</div>
</div>
<div class="row">
<div class="col-xl-3 mb-30 mb-30">
		<div class="card text-white border-secundary mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             <div class="card-header bg-secundary">
                 <div class="row">
                     
                     <div class="col-12 text-center">
                        <div class="huge"><h2><?= $count_ideas; ?>
							</h2></div>
                        <span class="h4">Ideas</span>
						
                     </div>
                 </div>
             </div>
			 
					<div class="card-footer">
						<span class="float-left text-success">
						<a href="index?ideas">	View Ideas</a>
						</span>
						<span class="float-right text-success">
							<i class="fa fa-arrow-circle-o-right"></i>
						</span>
						<div class="clearfix"></div>
					</div>
				</a>

             
        </div>    


	</div>
	<div class="col-xl-3 mb-30 mb-30">
		<div class="card text-white border-secundary mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             <div class="card-header bg-secundary">
                 <div class="row">
                     
                     <div class="col-12 text-center">
                        <div class="huge"><h2><?= $count_comments; ?>
							</h2></div>
                        <span class="h4">Comments in Ideas</span>
						
                     </div>
					 
                 </div>
             </div><div class="card-footer">
						<span class="float-left text-success">
						<a href="index?comments">	View Comments</a>
						</span>
						<span class="float-right text-success">
							<i class="fa fa-arrow-circle-o-right"></i>
						</span>
						<div class="clearfix"></div>
					</div>
             
        </div>    


	</div>



	</div>
</div>
	
</div>
</div>
<script>
	const alert = document.querySelector('.alert');
	const dismissAlertButton = document.querySelector('.alert button');
	function hideAlert() {
		alert.style.display = "none";
	}
	if (getCookie('hideAlert')) {
		hideAlert();
	}
	if (dismissAlertButton) {
		dismissAlertButton.addEventListener('click', event => {
			event.preventDefault();
			alert.classList.add('alert-hidden');
			setCookie('hideAlert', true, 10); // Setze Cookie mit 10 Minuten Ablaufzeit
		});
	}
	// Funktion zum Lesen eines Cookies
	function getCookie(name) {
		const value = `; ${document.cookie}`;
		const parts = value.split(`; ${name}=`);
		if (parts.length === 2) return parts.pop().split(';').shift();
	}
	// Funktion zum Setzen eines Cookies
	function setCookie(name, value, minutes) {
		const date = new Date();
		date.setTime(date.getTime() + (minutes * 60 * 1000));
		document.cookie = `${name}=${value}; expires=${date.toUTCString()}; path=/`;
	}
</script>