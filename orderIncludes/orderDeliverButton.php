<?php if($seller_id == $login_seller_id){ ?>
  <?php if($order_status == "progress" or $order_status == "revision requested"){ ?>
    <center>
      <button class="btn btn-success btn-lg mt-5 mb-3" data-toggle="modal" data-target="#deliver-order-modal">
      <i class="fa fa-upload"></i> <?= $lang['order_details']['deliver_order']; ?>
      </button>
    </center>
  <?php } ?>
  <?php if($order_status == "delivered"){ ?>
    <center>
      <button class="btn btn-success btn-lg mt-4 mb-2" data-toggle="modal" data-target="#deliver-order-modal">
      <i class="fa fa-upload"></i> <?= $lang['order_details']['deliver_order_again']; ?>
      </button>
    </center>
  <?php } ?>
<?php } ?>