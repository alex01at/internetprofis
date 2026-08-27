<?php

require_once("../functions/email.php");

$featured_proposal = $row_proposal->proposal_featured;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$featured_proposal_while_creating = $row_payment_settings->featured_proposal_while_creating;

$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$approve_proposals = $row_general_settings->approve_proposals;
if($approve_proposals == "yes"){
  $text = $lang['publish']['save_approve']; 
}else{ 
  $text = $lang['publish']['save_publish']; 
}

?>

<h1><img style="position:relative; top:-5px;" src="../images/comp/winner.png"> 
 <?=$lang['publish']['grat'];?></h1>

<h6 class="font-weight-normal line-height-normal">
  <?=$lang['publish']['grat'];?> <br>
  <?=$lang['publish']['all_ok'];?><br><br>
  <span class="text-muted">
  <?=$lang['publish']['draft'];?>
  </span>
</h6>

<form action="" method="post">
  <?php if($featured_proposal_while_creating == 1){ ?>
  <?php if($featured_proposal != "yes"){ ?>
  <h1 class="h3"><?=$lang['publish']['features'];?></h1>
  <h6 class="font-weight-normal line-height-normal">
    <?=$lang['publish']['features_text'];?>
    <p class="ml-4 mt-3">
      <label for="checkid" style="word-wrap:break-word">
        <input type="checkbox" id="checkid" name="proposal_featured" value="1" style="vertical-align:middle;margin-left: -1.25rem;"> <?=$lang['publish']['features'];?>
      </label>
    </p>
  </h6>
  <?php }} ?>
  <div class="form-group mb-0 mt-3"><!--- form-group Starts --->
    <a href="#" class="btn btn-secondary back-to-gallery"><?= $lang['button']['back']; ?></a>
    <input class="btn btn-success" type="submit" name="submit_proposal" value="<?= $text; ?>">
    <a href="#" class="btn btn-success d-none" id="featured-button"><?=$lang['publish']['features'];?></a>
  </div><!--- form-group Starts --->
</form>

<script>
$('.back-to-gallery').click(function(){
  $("input[type='hidden'][name='section']").val("gallery");
  $('#publish').removeClass('show active');
  $('#gallery').addClass('show active');
  $('#tabs a[href="#publish"]').removeClass('active');
});

$("input[name='proposal_featured']").change(function(){
  if (this.checked) {
    $("#featured-button").removeClass("d-none");
    $("input[name='submit_proposal'").addClass("d-none");
  }else{
    $("#featured-button").addClass("d-none");
    $("input[name='submit_proposal'").removeClass("d-none");
  }
});

$("#featured-button").click(function(){
  proposal_id = "<?= $proposal_id; ?>";
  $.ajax({
    method: "POST",
    url: "pay_featured_listing",
    data: {proposal_id: proposal_id, createProposal:1}
  }).done(function(data){
    $("#featured-proposal-modal").html(data); 
  });
});
</script>
<?php 
if(isset($_POST['submit_proposal'])){
  if($approve_proposals == "yes"){ 
    $status = "pending";
    $text = "Your Proposal Has Been Successfully Submitted For Approval."; 
  }else{ 
    $status = "active"; 
    $text = "Your Proposal Has Been Successfully Publish. Now Its Live"; 
  }
  $update_proposal = $db->update("proposals",array("proposal_status"=>$status),array("proposal_id"=>$proposal_id));
  if($update_proposal){
    if($row_general_settings->proposal_email == "yes"){
      $site_email_address = $row_general_settings->site_email_address;
      $get_meta = $db->select("cats_meta",array("cat_id" => $d_proposal_cat_id, "language_id" => $siteLanguage));
      $cat_title = $get_meta->fetch()->cat_title;

      $data = [];
      $data['template'] = "new_proposal";
      $data['to'] = $site_email_address;
      $data['subject'] = "$site_name - $login_seller_user_name Has Just Created A New Proposal.";
      $data['user_name'] = "";
      $data['seller_user_name'] = $login_seller_user_name;
      $data['proposal_title'] = $d_proposal_title;
      $data['cat_title'] = $cat_title;
      $data['status'] = ucfirst($status);
      send_mail($data);

      send_proposal_email($login_seller_user_name,$d_proposal_title,$cat_title,$status);

    }
    echo "<script>
    swal({
      type: 'success',
      text: '$text',
      timer: 2000,
      onOpen: function(){
        swal.showLoading()
      }
    }).then(function(){
      window.open('view_proposals','_self');
    });
    </script>";
  }
}
?>