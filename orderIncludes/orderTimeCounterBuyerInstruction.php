<?php if($order_status == "progress" or $order_status == "revision requested"){ ?>
  <?php if($seller_id == $login_seller_id){ ?>
  <h2 class="text-center mt-4" id="countdown-heading">
    <?= $lang['order_timer']['seller_deadline']; ?>
  </h2>
  <?php }elseif($buyer_id == $login_seller_id){ ?>
  <h2 class="text-center mt-4" id="countdown-heading">
    <?= $lang['order_timer']['buyer_deadline']; ?>
  </h2>
  <?php } ?>
  <div id="countdown-timer">
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 countdown-box">
        <p class="countdown-number" id="days"></p>
        <p class="countdown-title"><?= $lang['day']; ?></p>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 countdown-box">
        <p class="countdown-number" id="hours"></p>
        <p class="countdown-title"><?= $lang['order_timer']['hours']; ?></p>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 countdown-box">
        <p class="countdown-number" id="minutes"></p>
        <p class="countdown-title"><?= $lang['order_details']['minutes']; ?></p>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 countdown-box">
        <p class="countdown-number" id="seconds"></p>
        <p class="countdown-title"><?= $lang['order_timer']['seconds']; ?></p>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php if($buyer_id == $login_seller_id){ ?>
  <?php if(!empty($buyer_instruction)){ ?>
  <div class="card mb-3 mt-3">
    <!--- card mb-3 mt-3 Starts --->
    <div class="card-header">
      <h5><?= $lang['order_timer']['getting_started']; ?></h5>
    </div>
    <div class="card-body">
      <h6>
        <b><?= $seller_user_name; ?></b>
        <?= $lang['order_timer']['requires_information']; ?>
      </h6>
      <p>
        <?= $buyer_instruction; ?>
      </p>
    </div>
  </div>
  <!--- card mb-3 mt-3 Ends --->
  <?php } ?>
<?php } ?>