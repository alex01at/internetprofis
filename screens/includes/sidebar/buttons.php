<?php if($proposal_price != 0){ $price = $proposal_price; } ?>
<?php if(!isset($_SESSION['seller_user_name'])){ ?>
  <a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-order primary mb-3">
    <i class="fa fa-shopping-cart"></i> &nbsp;<strong><?= $lang['proposal']['nav']['add_to_cart'];?></strong>
  </a>
  <a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-order">
    <strong><?= $lang['proposal']['nav']['order_now']?> (<?= showPrice($price,"total-price"); ?>)</strong>
  </a>
<?php }else{ ?>
  <?php if($proposal_seller_user_name == @$_SESSION['seller_user_name']){ ?>
  <a class="btn btn-order" href="../edit_proposal.php?proposal_id=<?= $proposal_id; ?>">
   <i class="fa fa-edit"></i><?= $lang["titles"]["edit_proposal"]?>
  </a>
  <?php }else{ ?>
  <?php if($countcart == 1){ ?>
  <button type="button" class="btn btn-order primary added mb-3">
    <i class="fa fa-shopping-cart"></i> &nbsp;<strong>Already Added</strong>
  </button>
  <?php }else{ ?>
  <button type="submit" name="add_cart" value="1" class="btn btn-order primary mb-3">
   <i class="fa fa-shopping-cart"></i> &nbsp;<strong><?=$lang['add_to_cart']; ?></strong>
  </button>
  <?php } ?>
  <button type="submit" name="add_order" value="1" class="btn btn-order">
    <!-- <strong><?= $lang['proposal']['nav']['order_now']?> (<?= $s_currency; ?><span class="<?= $priceClass; ?>"><?= $price; ?></span>)</strong> -->
    <strong><?=$lang['buy_now'];?> (<?= showPrice($price,$priceClass); ?>)</strong>
  </button>
  <?php } ?>
<?php } ?>