<div class='card border-0'>
  <div class='card-body pb-3'>
    <h5 class='font-weight-normal mb-3'><strong><?=$lang["titles"]["how_it_works"];?></strong> <span class='badge badge-success badge-sm'><?= $lang['site_rule'];?></span> </h5>
    <div class='price'>
    <b class='currency'><?= $s_currency; ?><span><?= $proposal_price; ?></span></b>
    </div>
    <p class='h6 line-height-full'><?=$lang["site_rule_desc"]; ?></p>
  </div>
</div>
<script>
var order_box = $('.order-box');
$('.popover').css({ 'max-width' : order_box.width() + 'px', left : '155px' });
</script>
