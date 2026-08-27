<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{


$count_sellers = $db->count("sellers");
$count_notifications = $db->count("admin_notifications",array("status" => "unread"));
$count_orders = $db->count("orders",array("order_active" => "yes"));
$count_proposals = $db->count("proposals",array("proposal_status" => "pending"));
$count_support_tickets = $db->count("support_tickets",array("status" => "open"));
$count_requests = $db->count("buyer_requests",array("request_status" => "pending"));
$count_referrals = $db->count("referrals",array("status" => "pending"));
$count_proposals_referrals = $db->count("proposal_referrals",array("status" => "pending"));

function autoLoader($className){
  require_once("../functions/$className.php");
}
spl_autoload_register('autoLoader');

$core = new Core;
$paymentGateway = $core->checkPlugin("paymentGateway");
$videoPlugin = $core->checkPlugin("videoPlugin");;

?>
    <script>
    
    function alert_error(text){
        Swal('',text,'error');
    }

    function alert_success(text,url){
      swal({
      type: 'success',
      timer : 3000,
      text: text,
      onOpen: function(){
        swal.showLoading()
      }
      }).then(function(){
        if(url != ""){
            window.open(url,'_self');
        }
      });
    }
    
    function alert_error(text,url){
      swal({
      type: 'error',
      timer: 3000,
      text: text,
      onOpen: function(){
        swal.showLoading()
      }
      }).then(function(){
        if(url != ""){
            window.open(url,'_self');
        }
      });
    }
    
    function alert_confirm(text,url){
    swal({
      text: text,
      type: 'warning',
      showCancelButton: true 
    }).then((result) => {
        if(result.value){ 
            if(url != ""){
                window.open(url,'_self');
            }
        }
    });
    }

</script>
</head>
<body>
  <script src="assets/js/secret.js"></script>
  <!-- Left Panel -->
  <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
          <div class="navbar-header">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="index?dashboard">
            <?= $site_name; ?> <span class="badge badge-success p-2 font-weight-bold">ADMIN</span>
            </a>
            <a class="navbar-brand hidden" href="./"><span class="badge badge-success pt-2 pb-2">A</span></a>
          </div>
          <div id="main-menu" class="main-menu collapse navbar-collapse">
              <?php include("includes/sidebar.php"); ?>
          </div>
        </nav>
  </aside>
  <!-- Left Panel -->
<!-- Right Panel -->
<div id="right-panel" class="right-panel">
    <!-- Header-->
    <header id="header" class="header">
        <div class="header-menu">
            <?php include("includes/admin_header.php"); ?>
        </div>
    </header>
    <!-- Header-->
        

<div class="breadcrumbs">
  <div class="col-sm-4">
      <div class="page-header float-left">
          <div class="page-title">
              <h1><i class="menu-icon fa fa-table"></i> Proposals</h1>
          </div>
      </div>
  </div>
  <div class="col-sm-8">
      <div class="page-header float-right">
          <div class="page-title">
              <ol class="breadcrumb text-right">
                  <li class="active">Filter Proposals</li>
              </ol>
          </div>
      </div>
  </div>
</div>


<div class="main-container">
        
<div class="row"><!--- 1 row Starts --->

<div class="col-lg-12"><!--- col-lg-12 Starts --->

<div class="p-3 mb-3"><!--- p-3 mb-3 filter-form Starts --->

<h2 class="pb-4">Filter Proposals</h2>

<form class="form-inline pb-2" method="get" action="filter_proposals.php">

<div class="form-group">

<label> Category : </label>

<select name="cat_id" required class="form-control mb-2 ml-1 mr-sm-2 mb-sm-0">
<option value=""> Select A Seller Level </option>

<?php

$get_cat_id = $input->get('cat_id');

$get_categories = $db->select("categories");

while($row_categories = $get_categories->fetch()){
    
$cat_id = $row_categories->cat_id;

$get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $adminLanguage));

$cat_title = $get_meta->fetch()->cat_title;
    
echo "<option ".($get_cat_id == $cat_id ? "selected" : "")." value='$cat_id'>$cat_title</option>";

}

?>

</select>

</div>

<div class="form-group">

<label> Delivery Time : </label>

<select name="delivery_id" class="form-control mb-2 ml-1 mr-sm-2 mb-sm-0">

<option value=""> Select A Delivery Time </option>

<?php

$get_delivery_id = $input->get('delivery_id');


$get_delivery_times = $db->select("delivery_times");

while($row_delivery_times = $get_delivery_times->fetch()){

$delivery_id = $row_delivery_times->delivery_id;

$delivery_title = $row_delivery_times->delivery_title;
    
echo "<option ".($get_delivery_id == $delivery_id ? "selected" : "")." value='$delivery_id'>$delivery_title</option>";
    
}


?>

</select>

</div>

<div class="form-group">

<label> Seller Level : </label>

<select name="level_id" class="form-control mb-2 ml-1 mr-sm-2 mb-sm-0">

<?php

$get_level_id = $input->get('level_id');

$get_seller_levels = $db->select("seller_levels");

while($row_seller_levels = $get_seller_levels->fetch()){
    
$level_id = $row_seller_levels->level_id;

$level_title = $db->select("seller_levels_meta",array("level_id"=>$level_id,"language_id"=>$adminLanguage))->fetch()->title;
    
echo "<option ".($get_level_id == $level_id ? "selected" : "")." value='$level_id'>$level_title</option>";
    
}


?>

</select>

</div>

<button type="submit" class="btn btn-success"> Filter</button>

</form>

</div><!--- p-3 mb-3 filter-form Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 1 row Ends --->


<div class="row mt-3"><!--- 2 row mt-3 Starts --->

<div class="col-lg-12"><!--- col-lg-12 Starts --->

<div class="card"><!--- card Starts --->

<div class="card-header"><!--- card-header Starts --->

<h4 class="h4">View Proposals</h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<div class="table-responsive"><!--- table-responsive mt-4 Starts --->

<table class="table table-bordered"><!--- table table-hover table-bordered Starts --->

<thead><!--- thead Starts --->

<tr>

<th>Proposal's Title</th>

<th>Proposal's Display Image</th>

<th>Proposal's Price</th>

<th>Proposal's Category</th>

<th>Proposal's Order Queue</th>

<th>Proposal's Status</th>

<th>Proposal's Action Options</th>

</tr>

</thead><!--- thead Ends --->

<tbody><!--- tbody Starts --->

<?php

$values = array();

$query_where = "";

if(isset($_GET['cat_id'])){

  $query_where .= "proposal_cat_id=:c_id";
  $values['c_id'] = $get_cat_id;
}

if(!empty($_GET['delivery_id'])){
  $query_where .= " AND delivery_id=:d_id";
  $values['d_id'] = $get_delivery_id;
}

if(!empty($_GET['level_id'])){
  $query_where .= " AND level_id=:d_id";
  $values['d_id'] = $get_level_id;
}


// echo $query_where."<br><br>";

$get_proposals = $db->query("select * from proposals where proposal_status not in ('modification','draft','deleted') and ($query_where) order by 1 DESC",$values);

$count_proposals = $get_proposals->rowCount();

if ($count_proposals == 0) {
  echo "<tr><td colspan='9'><h3 class='text-center'><i class='fa fa-meh-o'></i> We haven't found any proposals matching that search </h3></td></tr>";
}

while($row_proposals = $get_proposals->fetch()){

$proposal_id = $row_proposals->proposal_id;
$proposal_title = $row_proposals->proposal_title;
$proposal_url = $row_proposals->proposal_url;
$proposal_price = $row_proposals->proposal_price;
$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
$proposal_cat_id = $row_proposals->proposal_cat_id;
$proposal = $row_proposals->proposal_cat_id;
$proposal_seller_id = $row_proposals->proposal_seller_id;
$proposal_status = $row_proposals->proposal_status;
$proposal_seller_id = $row_proposals->proposal_seller_id;
$proposal_featured = $row_proposals->proposal_featured;
$proposal_toprated = $row_proposals->proposal_toprated;

if($proposal_price == 0){

$proposal_price = "";

$get_p = $db->select("proposal_packages",array("proposal_id" => $proposal_id));

while($row = $get_p->fetch()){

$proposal_price .=" | $s_currency" . $row->price;

}

}else{

$proposal_price = "$s_currency" . $proposal_price;

}


$select_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));

$seller_user_name = $select_seller->fetch()->seller_user_name;


$select_orders = $db->query("select * from orders where proposal_id='$proposal_id' AND NOT order_status='complete' AND proposal_id='$proposal_id' AND NOT order_status='cancelled'");

$proposal_order_queue = $select_orders->rowCount();


$get_meta = $db->select("cats_meta",array("cat_id" => $proposal_cat_id, "language_id" => $adminLanguage));

$cat_title = $get_meta->fetch()->cat_title;

?>

<tr>

<td><?= $proposal_title; ?></td>

<td>

<img src="<?= $proposal_img1; ?>" width="70" height="60">

</td>

<td><?= $proposal_price; ?></td>

<td><?= $cat_title; ?></td>

<td><?= $proposal_order_queue; ?></td>

<td><?= ucfirst($proposal_status); ?></td>
<?php if($proposal_status == "active"){ ?>

<td>

<a title="View Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

<i class="fa fa-eye"></i> 

</a>

<?php if($proposal_featured == "yes"){ ?>

<a class="text-success" title="Remove Proposal From Featured Listing." href="index?remove_feature_proposal=<?= $proposal_id; ?>"/>

    <i class="fa fa-star-half-o"></i>

</a>

<?php }else{ ?>

    <a href="index?feature_proposal=<?= $proposal_id; ?>" title="Make Your Proposal Featured">
        <i class="fa fa-star"></i>
    </a>

<?php } ?>

<?php if($proposal_toprated == 0){ ?>
<a href="index?toprated_proposal=<?= $proposal_id; ?>" title="Make Your Proposal Top Rated">
<i class="fa fa-heart" aria-hidden="true"></i>
</a>
<?php }else{ ?>
<a class="text-danger" href="index?removetoprated_proposal=<?= $proposal_id; ?>" title="Remove Proposal From Top Rated Listing.">
<i class="fa fa-heartbeat" aria-hidden="true"></i>
</a>
<?php } ?>

<a title="Pause/Deactivate Proposal" href="index?pause_proposal=<?= $proposal_id; ?>">

<i class="fa fa-pause-circle"></i> 

</a>


<a title="Delete Proposal" href="index?move_to_trash=<?= $proposal_id; ?>">

<i class="fa fa-trash"></i>

</a>


</td>

<?php }elseif($proposal_status == "pause" or $proposal_status == "admin_pause"){ ?>

<td>

<a title="View Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

<i class="fa fa-eye"></i> Preview

</a>

<br>

<a href="index?unpause_proposal=<?= $proposal_id; ?>" >

<i class="fa fa-refresh"></i> Unpause 

</a>
    
<br>

<a href="index?move_to_trash=<?= $proposal_id; ?>">

<i class="fa fa-trash"></i> Trash Proposal

</a>

</td>

<?php }elseif($proposal_status == "pending"){ ?>

<td>

<a title="Decline this Proposal" href="index?decline_proposal=<?= $proposal_id; ?>">

<i class="fa fa-ban"></i> Decline

</a>

<br/>

<a href="index?approve_proposal=<?= $proposal_id; ?>">

<i class="fa fa-check-square-o"></i> Approve

</a>

<br/>
    
<a title="View Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

<i class="fa fa-eye"></i> Preview

</a>

<br>

<a href="index?submit_modification=<?= $proposal_id; ?>">

<i class="fa fa-edit"></i> Submit For Modification

</a>

</td>

<?php }elseif($proposal_status == "declined"){ ?>

<td>

<a href="index?approve_proposal=<?= $proposal_id; ?>">

<i class="fa fa-check-square-o"></i> Approve

</a>

<br/>

<a href="index?submit_modification=<?= $proposal_id; ?>">

<i class="fa fa-edit"></i> Submit For Modification

</a>

<br>
    
<a title="View Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

<i class="fa fa-eye"></i> Preview

</a>

<br/>

<a href="index?delete_proposal=<?= $proposal_id; ?>">

<i class="fa fa-trash"></i> Delete Permanently

</a>

</td>

<?php }elseif($proposal_status == "trash"){ ?>

<td>

<a href="index?restore_proposal=<?= $proposal_id; ?>">

<i class="fa fa-reply"></i> Restore Proposal

</a>

<br/>

<a href="index?delete_proposal=<?= $proposal_id; ?>">

<i class="fa fa-trash"></i> Delete Permanently

</a>

</td>


<?php } ?>

</tr>

<?php } ?>

</tbody><!--- tbody Ends --->
</table><!--- table table-hover table-bordered Ends --->
</div><!--- table-responsive mt-4 Ends --->
</div><!--- card-body Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 2 row mt-3 Ends --->
</div>       

<div class="container clearfix">
<div class="row">
<div id="languagePanel" class="bg-light col-md-12 p-2 pb-0 mb-0"><!--- languagePanel Starts --->
    <div class="row">
    <div class="col-md-6"><!--- col-md-6 Starts --->
    <p class="col-form-label font-weight-normal mb-0 pb-0">Current Selected Language: <strong><?= $currentLanguage; ?></strong></p>
    </div><!--- col-md-6 Ends --->
    <div class="col-md-6 float-right"><!--- col-md-6 Starts --->
    <div class="form-group row mb-0 pb-0"><!--- form-group row Starts --->
        <label class="col-md-2"></label>
        <label class="col-md-4 col-form-label"> Change Language: </label>
        <div class="col-md-6">
        <select id="languageSelect" class="form-control">
        <?php
        $get_languages = $db->select("languages");
        while($row_languages = $get_languages->fetch()){
        $id = $row_languages->id;
        $title = $row_languages->title;
        ?>
        <option data-url="<?= "$site_url/admin/index?change_language=$id"; ?>" <?php if($id == $_SESSION["adminLanguage"]){ echo "selected"; } ?>>
        <?= $title; ?>
        </option>
    <?php } ?>
        </select>
        </div>
    </div><!--- form-group row Ends --->
    </div><!--- col-md-6 Ends -->
    </div>
</div><!--- languagePanel Ends --->
</div>
</div>

<br><br><br>

<script>
$(document).ready(function(){

    $("#languageSelect").change(function(){
        var url = $("#languageSelect option:selected").data("url");
        window.location.href = url;
    });

});
</script>

<?php } ?>
