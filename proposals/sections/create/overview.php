<?php 

$form_errors = Flash::render("form_errors");
$form_data = Flash::render("form_data");
if (empty($form_data)) {
  $form_data = $input->post();
}

?>

<form action="#" method="post" class="proposal-form">

  <div class="form-group row">
    <div class="col-md-3"><?= $lang['label']['proposal_title']; ?></div>
    <div class="col-md-9">
      <textarea name="proposal_title" rows="3" required placeholder="<?=$lang['placeholder']['i_will'];?>" class="form-control"><?= @$form_data['proposal_title']; ?></textarea>
    </div>
    <small class="form-text text-danger"><?= ucfirst(@$form_errors['proposal_title']); ?></small>
  </div>

  <div class="form-group row">
    <div class="col-md-3"><?= $lang['label']['category']; ?></div>
    <div class="col-md-9">
      <select name="proposal_cat_id" id="category" class="form-control mb-3" required>
        <option value="" class="d-none"><?=$lang['placeholder']['select_category'];?></option>
        <?php 
          $get_cats = $db->select("categories");
          while($row_cats = $get_cats->fetch()){
            $cat_id = $row_cats->cat_id;
            $get_meta = $db->select("cats_meta", ["cat_id" => $cat_id, "language_id" => $siteLanguage]);
            $cat_title = $get_meta->fetch()->cat_title;
            $selected = (@$form_data['proposal_cat_id'] == $cat_id) ? "selected" : "";
        ?>
        <option <?= $selected; ?> value="<?= $cat_id; ?>"><?= $cat_title; ?></option>
        <?php } ?>
      </select>
      <small class="form-text text-danger"><?= ucfirst(@$form_errors['proposal_cat_id']); ?></small>
      
      <select name="proposal_child_id" id="sub-category" class="form-control" required>
        <option value="" class="d-none"><?= $lang['proposals']['select_sub_category']; ?></option>
        <?php if(@$form_data['proposal_child_id']): ?>
          <?php
            $get_c_cats = $db->select("categories_children", ["child_parent_id" => $form_data['proposal_cat_id']]);
            while($row_c_cats = $get_c_cats->fetch()){
              $child_id = $row_c_cats->child_id;
              $get_meta = $db->select("child_cats_meta", ["child_id" => $child_id, "language_id" => $siteLanguage]);
              $child_title = $get_meta->fetch()->child_title;
              $selected = (@$form_data['proposal_child_id'] == $child_id) ? "selected" : "";
              echo "<option $selected value='$child_id'>$child_title</option>";
            }
          ?>
        <?php endif; ?>
      </select>
    </div>
  </div>

  <?php if($enable_referrals == "yes"){ ?>
    <div class="form-group row">
      <label class="col-md-3 col-form-label"><?= $lang['label']['enable_referrals']; ?></label>
      <div class="col-md-9">
        <select name="proposal_enable_referrals" class="proposal_enable_referrals form-control">
          <option value="no" <?= (@$form_data['proposal_enable_referrals'] == "no") ? "selected" : ""; ?>><?=$lang["no"];?></option>
          <option value="yes" <?= (@$form_data['proposal_enable_referrals'] == "yes") ? "selected" : ""; ?>><?=$lang["yes"];?></option>
        </select>
        <small><?=$lang["promote_proposal"];?></small>
      </div>
    </div>

    <div class="form-group row proposal_referral_money" style="display: none;">
      <label class="col-md-3 col-form-label"><?= $lang['label']['promotion_commission']; ?></label>
      <div class="col-md-9">
        <input type="number" name="proposal_referral_money" class="form-control" min="1" value="<?= @$form_data['proposal_referral_money']; ?>" placeholder="e.g. 20">
        <small><?= $lang["promote_proposal_desc_1"]; ?></small>
      </div>
    </div>
  <?php } ?>

  <div class="form-group row">
    <div class="col-md-3"><?= $lang['label']['tags']; ?></div>
    <div class="col-md-9">
      <input type="text" name="proposal_tags" class="form-control" data-role="tagsinput" value="<?= @$form_data['proposal_tags']; ?>">
      <small class="form-text text-danger"><?= $lang['tags_error']; ?></small>
    </div>
  </div>

  <div class="form-group text-right mb-0">
    <a href="view_proposals" class="btn btn-secondary"><?= $lang['button']['cancel']; ?></a>
    <input class="btn btn-success" type="submit" name="submit" value="<?= $lang['button']['save_continue']; ?>">
  </div>

</form>

<?php 

function insertPackages($proposal_id){
  global $db;
  $db->insert("proposal_packages", ["proposal_id" => $proposal_id, "package_name" => 'Basic', "price" => 5]);
  $db->insert("proposal_packages", ["proposal_id" => $proposal_id, "package_name" => 'Standard', "price" => 10]);
  $db->insert("proposal_packages", ["proposal_id" => $proposal_id, "package_name" => 'Advance', "price" => 15]);
  return true;
}

include("sanitize_url.php");

if(isset($_POST['submit'])){

  $rules = [
    "proposal_title" => "required",
    "proposal_cat_id" => "required",
    "proposal_child_id" => "required",
    "proposal_tags" => "required"
  ];

  $messages = [
    "proposal_cat_id" => $lang['cat_error'],
    "proposal_child_id" => $lang['child_error']
  ];

  $val = new Validator($_POST, $rules, $messages);

  if($val->run() == false){
    Flash::add("form_errors", $val->get_all_errors());
    Flash::add("form_data", $_POST);
    echo "<script> window.open('create_proposal','_self');</script>";
  } else {

    $proposal_title = $input->post('proposal_title');
    $sanitize_url = proposalUrl($proposal_title);

    // Check auf doppelte Titel
    $check_title = $db->select("proposals", ["proposal_seller_id" => $login_seller_id, "proposal_url" => $sanitize_url]);
    
    if($check_title->rowCount() > 0){
      echo "<script>swal({ type: 'warning', text: 'Oops! You already have a proposal with this title.' })</script>";
    } else {

      $data = $input->post();
      unset($data['submit']);

      $data['proposal_url'] = $sanitize_url;
      $data['proposal_seller_id'] = $login_seller_id;
      $data['proposal_featured'] = "no";
      $data['proposal_price'] = 0;
      $data['proposal_status'] = "draft";
      
      $data['level_id'] = (!empty($login_seller_level)) ? $login_seller_level : 1;
      $data['language_id'] = (!empty($login_seller_language)) ? $login_seller_language : 1;
      
      $get_delivery = $db->query("select * from delivery_times LIMIT 1")->fetch();
      $data['delivery_id'] = ($get_delivery) ? $get_delivery->delivery_id : 1;

      if($enable_referrals == "no"){ 
        $data['proposal_enable_referrals'] = "no"; 
      }

      $insert_proposal = $db->insert("proposals", $data);

      if($insert_proposal){
        $proposal_id = $db->lastInsertId();
        $db->insert("instant_deliveries", ["proposal_id" => $proposal_id]);

        if($videoPlugin == 1){
          $cat_id = $input->post("proposal_cat_id");
          $child_id = $input->post("proposal_child_id");
          include("$dir/plugins/videoPlugin/proposals/checkVideo.php");
        } else {
          $redirect = "instant_delivery";
        }

        insertPackages($proposal_id);

        echo "<script>
        swal({
          type: 'success',
          text: 'Details Saved.',
          timer: 2000,
          onOpen: function(){ swal.showLoading() }
        }).then(function(){
          window.open('edit_proposal?proposal_id=$proposal_id&$redirect','_self')
        });
        </script>";
      }
    }
  }
}
?>