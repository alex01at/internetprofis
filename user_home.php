<?php
  $login_seller_user_name = $_SESSION['seller_user_name'];
  $select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
  $row_login_seller = $select_login_seller->fetch();
  $login_seller_id = $row_login_seller->seller_id;
  $login_seller_name = $row_login_seller->seller_name;
  $login_user_name = $row_login_seller->seller_user_name;
  $login_seller_offers = $row_login_seller->seller_offers;
  $relevant_requests = $row_general_settings->relevant_requests;

  // Shopping Balance
  $select_seller_accounts = $db->select("seller_accounts",array("seller_id"=>$login_seller_id));
  $row_seller_accounts = $select_seller_accounts->fetch();
  $current_balance = $row_seller_accounts->current_balance;

  // Pending Orders
  $count_orders = $db->count("orders",array("seller_id"=>$login_seller_id, "order_status"=>"pending"));

  // Completed Orders
  $count_completed_orders = $db->count("orders",array("seller_id"=>$login_seller_id, "order_status"=>"completed"));

  // Total Active Proposals
  $count_active_proposals = $db->count("proposals",array("proposal_seller_id"=>$login_seller_id, "proposal_status"=>"active"));
?>

<style>
  .carousel-item img,
  .carousel-item video{
    height: auto !important;
    background-color: black;
  }
</style>
<div class="container mt-3">
  <!-- Container starts -->
  <div class="row">
    <div class="col-md-4 <?=($lang_dir == "right" ? 'order-2 order-sm-1':'')?>">
      <?php require_once("includes/user_home_sidebar.php"); ?>
    </div>
    <div class="col-md-8 <?=($lang_dir == "right" ? 'order-1 order-sm-2':'')?>">
          <div class="card rounded-0 mb-4">
            <div class="card-body">
                <h3 class="mb-0">
                <?php
                $hour = date("H");
                if($hour < 12){
                echo $lang['greetings']['morning'];
                }elseif($hour < 18){
                echo $lang['greetings']['afternoon'];
                }else{
                echo $lang['greetings']['evening'];
                }
                ?>, <?= $login_seller_name; ?>!
                </h3>
            </div>
        </div>
    
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-dashboard shadow-sm h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center">
                                <i class="fa fa-wallet fa-2x text-success"></i>
                            </div>
                            <div class="col-9">
                                <h5 class="card-title text-muted"><?= $lang["dashboard"]['balance']; ?></h5>
                                <h4 class="card-text"><?= showPrice($current_balance); ?></h4>
                            </div>
                        </div>
                        <a href="revenue" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-dashboard shadow-sm h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center">
                                <i class="fa fa-spinner fa-2x text-warning"></i>
                            </div>
                            <div class="col-9">
                                <h5 class="card-title text-muted"><?= $lang["dashboard"]['pending']; ?></h5>
                                <h4 class="card-text"><?= $count_orders; ?></h4>
                            </div>
                        </div>
                        <a href="selling_orders" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-dashboard shadow-sm h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center">
                                <i class="fa fa-check fa-2x text-primary"></i>
                            </div>
                            <div class="col-9">
                                <h5 class="card-title text-muted"><?= $lang["dashboard"]['completed']; ?></h5>
                                <h4 class="card-text"><?= $count_completed_orders; ?></h4>
                            </div>
                        </div>
                        <a href="selling_history" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-dashboard shadow-sm h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center">
                                <i class="fa fa-bullhorn fa-2x text-info"></i>
                            </div>
                            <div class="col-9">
                                <h5 class="card-title text-muted"><?= $lang["dashboard"]['active_gigs']; ?></h5>
                                <h4 class="card-text"><?= $count_active_proposals; ?></h4>
                            </div>
                        </div>
                        <a href="proposals/view_proposals" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>
    
      <div id="demo3" class="carousel slide shadow-sm rounded overflow-hidden mt-3">        <ul class="carousel-indicators">
          <li data-target="#demo3" data-slide-to="0" class="active"></li>
          <?php
            $count_slides = $db->count("slider",array("language_id" => $siteLanguage));
            $i = 0;
            $get_slides = $db->query("select * from slider where language_id='$siteLanguage' LIMIT 1,$count_slides");
            while($row_slides = $get_slides->fetch()){
            $i++;
            ?>
          <li data-target="#demo3" data-slide-to="<?= $i; ?>"></li>
          <?php } ?>
        </ul>
        <div class="carousel-inner">
          <?php
            $i = 0;
            $get_slides = $db->query("select * from slider where language_id='$siteLanguage'");
            while($row_slides = $get_slides->fetch()){
              $slide_image = getImageUrl("slider",$row_slides->slide_image); 
              $slide_name = $row_slides->slide_name;
              $slide_desc = $row_slides->slide_desc;
              $slide_url = $row_slides->slide_url;
              $s_extension = pathinfo($slide_image, PATHINFO_EXTENSION);
              $i++;
            ?>
          <div class="carousel-item <?= ($i == 1 ? "active" : "") ?>">
              <?php if($s_extension == "mp4" or $s_extension == "webm" or $s_extension == "ogg"){ ?>
                <video class="img-fluid w-100" controls muted <?= ($i == 1 ? "autoplay" : "") ?>>
                  <source src="<?= $slide_image; ?>" type="video/mp4">
                </video>
              <?php }else{ ?>
                <a href="<?= $slide_url; ?>"> <img src="<?= $slide_image; ?>" class="img-fluid"> </a>
              <?php } ?>
              <div class="carousel-caption d-lg-block d-md-block d-none <?=($lang_dir == "right"?'text-right':'')?>"/>
                <h3><?= $slide_name; ?></h3>
                <p><?= $slide_desc; ?></p>
              </div>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="row mt-5 mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
          <h2 class="mb-0 <?=($lang_dir == "right" ? 'text-right':'text-left')?>"><?= $lang['user_home']['featured_proposals']; ?></h2>
          <button onclick="location.href='featured_proposals'" class="btn btn-outline-success btn-sm"><?= $lang['view_all']; ?></button>
        </div>
      </div>
      <div class="row">
        <?php
          $get_proposals = $db->query("select * from proposals where proposal_featured='yes' AND proposal_status='active' LIMIT 0,8");
          $count_proposals = $get_proposals->rowCount();
          if($count_proposals == 0){
              echo "
              <div class='col-md-12 text-center py-5 bg-light rounded shadow-sm'>
              <p class='text-muted lead mb-0'><i class='fa fa-frown-o'></i> {$lang['user_home']['no_featured_proposals']} </p>
              </div>";
          }
          while($row_proposals = $get_proposals->fetch()){
          $proposal_id = $row_proposals->proposal_id;
          $proposal_title = $row_proposals->proposal_title;
          $proposal_price = $row_proposals->proposal_price;
          if($proposal_price == 0){
          $get_p_1 = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
          $proposal_price = $get_p_1->fetch()->price;
          }
          $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
          $proposal_video = $row_proposals->proposal_video;
          $proposal_seller_id = $row_proposals->proposal_seller_id;
          $proposal_rating = $row_proposals->proposal_rating;
          $proposal_url = $row_proposals->proposal_url;
          $proposal_featured = $row_proposals->proposal_featured;
          $proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
          $proposal_referral_money = $row_proposals->proposal_referral_money;
          if(empty($proposal_video)){
              $video_class = "";
          }else{
              $video_class = "video-img";
          }
          $get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
          $row_seller = $get_seller->fetch();
          $seller_user_name = $row_seller->seller_user_name;
          $seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);
          $seller_level = $row_seller->seller_level;
          $seller_status = $row_seller->seller_status;
          if(empty($seller_image)){
          $seller_image = "empty-image.png";
          }
          // Select Proposal Seller Level
          @$seller_level = $db->select("seller_levels_meta",array("level_id"=>$seller_level,"language_id"=>$siteLanguage))->fetch()->title;
          $proposal_reviews = array();
          $select_buyer_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id));
          $count_reviews = $select_buyer_reviews->rowCount();
          while($row_buyer_reviews = $select_buyer_reviews->fetch()){
            $proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
            array_push($proposal_reviews,$proposal_buyer_rating);
          }
          $total = array_sum($proposal_reviews);
          if (count($proposal_reviews) > 0) {
              $average_rating = $total / count($proposal_reviews);
          } else {
              $average_rating = 0;
          }
          
          $count_favorites = $db->count("favorites",array("proposal_id" => $proposal_id,"seller_id" => $login_seller_id));
          if($count_favorites == 0){
          $show_favorite_class = "proposal-favorite";
          }else{
          $show_favorite_class = "proposal-unfavorite";
          }
          ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3 pr-lg-1">
          <?php require("includes/proposals.php"); ?>
        </div>
        <?php } ?>
      </div>
      <!-- If You have no gigs, show random gigs on homepage -->
      <div class="row mt-5 mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
          <h2 class="mb-0 <?=($lang_dir == "right" ? 'text-right':'text-left')?>"><?= $lang['user_home']['top_proposals']; ?></h2>
          <button onclick="location.href='top_proposals'" class="btn btn-outline-success btn-sm"><?= $lang['view_all']; ?></button>
        </div>
      </div>
      <div class="row">
        <?php
          $topProposals = array();
          $select = $db->query("select * from top_proposals");
          while($row = $select->fetch()){
            array_push($topProposals,  $row->proposal_id);
          }
          if(empty($topProposals)){
          $query_where2 = "where level_id='4' and proposal_status='active' ";
          }else{
          $topProposals = implode(",", $topProposals);
          $topRatedWhere = "level_id='4' and proposal_status='active'";
          $query_where2 = "where proposal_id in ($topProposals) or ($topRatedWhere) ";
          }
          $get_proposals = $db->query("select * from proposals $query_where2 LIMIT 0,8");
          $count_proposals = $get_proposals->rowCount();
          if($count_proposals == 0){
            echo "
            <div class='col-md-12 text-center py-5 bg-light rounded shadow-sm'>
            <p class='text-muted lead mb-0'><i class='fa fa-frown-o'></i> {$lang['user_home']['no_top_proposals']} </p>
            </div>";
          }
          while($row_proposals = $get_proposals->fetch()){
          $proposal_id = $row_proposals->proposal_id;
          $proposal_title = $row_proposals->proposal_title;
          $proposal_price = $row_proposals->proposal_price;
          if($proposal_price == 0){
          $get_p_1 = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
          $proposal_price = $get_p_1->fetch()->price;
          }
          $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
          $proposal_video = $row_proposals->proposal_video;
          $proposal_seller_id = $row_proposals->proposal_seller_id;
          $proposal_rating = $row_proposals->proposal_rating;
          $proposal_url = $row_proposals->proposal_url;
          $proposal_featured = $row_proposals->proposal_featured;
          $proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
          $proposal_referral_money = $row_proposals->proposal_referral_money;
          if(empty($proposal_video)){
              $video_class = "";
          }else{
              $video_class = "video-img";
          }
          $get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
          $row_seller = $get_seller->fetch();
          $seller_user_name = $row_seller->seller_user_name;
          $seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);
          $seller_level = $row_seller->seller_level;
          $seller_status = $row_seller->seller_status;
          if(empty($seller_image)){
          $seller_image = "empty-image.png";
          }
          // Select Proposal Seller Level
          @$seller_level = $db->select("seller_levels_meta",array("level_id"=>$seller_level,"language_id"=>$siteLanguage))->fetch()->title;
          $proposal_reviews = array();
          $select_buyer_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id));
          $count_reviews = $select_buyer_reviews->rowCount();
          while($row_buyer_reviews = $select_buyer_reviews->fetch()){
              $proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
              array_push($proposal_reviews,$proposal_buyer_rating);
          }
          if (count($proposal_reviews) > 0) {
            $total = array_sum($proposal_reviews);
            $average_rating = $total / count($proposal_reviews);
          } else {
            $average_rating = 0;
          }
        
          $count_favorites = $db->count("favorites",array("proposal_id" => $proposal_id,"seller_id" => $login_seller_id));
          if($count_favorites == 0){
          $show_favorite_class = "proposal-favorite";
          }else{
          $show_favorite_class = "proposal-unfavorite";
          }
        ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3 pr-lg-1">
          <?php require("includes/proposals.php"); ?>
        </div>
        <?php } ?>
      </div>
      <!-- If You have no gigs, show random gigs on homepage -->
      <div class="row mt-5 mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
          <h2 class="mb-0 <?=($lang_dir == "right" ? 'text-right':'text-left')?>"><?= $lang['user_home']['random_proposals']; ?></h2>
          <button onclick="location.href='random_proposals'" class="btn btn-outline-success btn-sm"><?= $lang['view_all']; ?></button>
        </div>
      </div>
      <div class="row">
        <?php
          $get_proposals = $db->query("select * from proposals where proposal_status='active' order by rand() LIMIT 0,8");
          $count_proposals = $get_proposals->rowCount();
          if($count_proposals == 0){
              echo "
              <div class='col-md-12 text-center py-5 bg-light rounded shadow-sm'>
              <p class='text-muted lead mb-0'><i class='fa fa-frown-o'></i> {$lang['user_home']['no_random_proposals']} </p>
              </div>";
          }
          while($row_proposals = $get_proposals->fetch()){
          $proposal_id = $row_proposals->proposal_id;
          $proposal_title = $row_proposals->proposal_title;
          $proposal_price = $row_proposals->proposal_price;
          if($proposal_price == 0){
          $get_p_1 = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
          $proposal_price = $get_p_1->fetch()->price;
          }
          $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
          $proposal_video = $row_proposals->proposal_video;
          $proposal_seller_id = $row_proposals->proposal_seller_id;
          $proposal_rating = $row_proposals->proposal_rating;
          $proposal_url = $row_proposals->proposal_url;
          $proposal_featured = $row_proposals->proposal_featured;
          $proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
          $proposal_referral_money = $row_proposals->proposal_referral_money;
          if(empty($proposal_video)){
            $video_class = "";
          }else{
            $video_class = "video-img";
          }
          $get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
          $row_seller = $get_seller->fetch();
          $seller_user_name = $row_seller->seller_user_name;
          $seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);
          $seller_level = $row_seller->seller_level;
          $seller_status = $row_seller->seller_status;
          if(empty($seller_image)){
          $seller_image = "empty-image.png";
          }
          // Select Proposal Seller Level
          @$seller_level = $db->select("seller_levels_meta",array("level_id"=>$seller_level,"language_id"=>$siteLanguage))->fetch()->title;
          $proposal_reviews = array();
          $select_buyer_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id));
          $count_reviews = $select_buyer_reviews->rowCount();
          while($row_buyer_reviews = $select_buyer_reviews->fetch()){
              $proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
              array_push($proposal_reviews,$proposal_buyer_rating);
          }
          $total = array_sum($proposal_reviews);
          if (count($proposal_reviews) > 0) {
            $average_rating = $total / count($proposal_reviews);
          } else {
            $average_rating = 0;
          }
        
          $count_favorites = $db->count("favorites",array("proposal_id" => $proposal_id,"seller_id" => $login_seller_id));
          if($count_favorites == 0){
            $show_favorite_class = "proposal-favorite";
          }else{
            $show_favorite_class = "proposal-unfavorite";
          }
          ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3 pr-lg-1">
          <?php require("includes/proposals.php"); ?>
        </div>
        <?php } ?>
      </div>
      <br>
      <!-- If You have no gigs, show random gigs on homepage Ends -->
      <?php
        $request_child_ids = array();
        $select_proposals = $db->query("select DISTINCT proposal_child_id from proposals where proposal_seller_id='$login_seller_id' and proposal_status='active'");
        while($row_proposals = $select_proposals->fetch()){
        $proposal_child_id = $row_proposals->proposal_child_id;
        array_push($request_child_ids, $proposal_child_id);
        }
        $where_child_id = array();
        foreach($request_child_ids as $child_id){
            $where_child_id[] = "child_id=" . $child_id;
        }
        if(count($where_child_id) > 0){
            $query_where = " and (" . implode(" or ", $where_child_id) . ")";
        }
        
        if($relevant_requests == "no"){ $query_where = ""; }

        if(!empty($query_where) or $relevant_requests == "no"){
        
        $select_requests =  $db->query("select * from buyer_requests where request_status='active'". $query_where ." AND NOT seller_id='$login_seller_id' order by request_id DESC LIMIT 0,5");
        $requests_count = 0;
        while($row_requests = $select_requests->fetch()){
            $request_id = $row_requests->request_id;
            $count_offers = $db->count("send_offers",array("request_id" => $request_id,"sender_id" => $login_seller_id));
            if($count_offers == 0){
                $requests_count++;
            }
        }
        
        $count_proposals = $db->count("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>'active'));
        
        if($requests_count !=0 and !empty($count_proposals)){

        ?>
      <div class="row mt-5 mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
          <h2 class="mb-0 <?=($lang_dir == "right" ? 'text-right':'text-left')?>"><?= $lang['user_home']['recent_requests']; ?></h2>
          <button type="button" onclick="location.href='requests/buyer_requests'" class="btn btn-outline-success btn-sm"><?= $lang['view_all']; ?></button>
        </div>
      </div>
      <div class="row buyer-requests mb-5">
        <div class="col-md-12">
          <div class="card border-0 shadow-sm rounded">
            <div class="card-body p-0">
          <div class="table-responsive box-table m-0">
            <table class="table table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th><?= $lang['requests']['request_message']; ?></th>
                  <th><?= $lang['th']['offers']; ?></th>
                  <th><?= $lang['th']['duration']; ?></th>
                  <th><?= $lang['th']['budget']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $select_requests =  $db->query("select * from buyer_requests where request_status='active'". $query_where ." AND NOT seller_id='$login_seller_id' order by request_id DESC LIMIT 0,5");
                  while($row_requests = $select_requests->fetch()){
                  $request_id = $row_requests->request_id;
                  $seller_id = $row_requests->seller_id;
                  $request_title = $row_requests->request_title;
                  $request_description = $row_requests->request_description;
                  $delivery_time = $row_requests->delivery_time;
                  $request_budget = $row_requests->request_budget;
                  $request_file = $row_requests->request_file;

                  $select_request_seller = $db->select("sellers",array("seller_id"=>$seller_id));
                  $row_request_seller = $select_request_seller->fetch();
                  $request_seller_user_name = $row_request_seller->seller_user_name;
                  $request_seller_image = getImageUrl2("sellers","seller_image",$row_request_seller->seller_image);
                  $count_send_offers = $db->count("send_offers",array("request_id" => $request_id));
                  $count_offers = $db->count("send_offers",array("request_id" => $request_id,"sender_id" => $login_seller_id));
                  if($count_offers == 0){
                  ?>
                <tr id="request_tr_<?= $request_id; ?>">
                  <td>
                    <a href="<?= $request_seller_user_name; ?>" target="_blank">
                      <?php if(!empty($request_seller_image)){ ?>
                        <img src="<?= $request_seller_image; ?>" class="request-img rounded-circle shadow-sm">
                      <?php }else{ ?>
                        <img src="empty-image.png" class="request-img rounded-circle shadow-sm">
                      <?php } ?>
                    </a>
                    <div class="request-description">
                      <h6>
                        <a href="<?= $request_seller_user_name; ?>" target="_blank">
                          <?= ucfirst($request_seller_user_name); ?>
                        </a>
                      </h6>
                      <h6 class="text-success"><?= $request_title; ?></h6>
                      <p class="lead"><?= $request_description; ?> </p>
                      <?php if(!empty($request_file)){ ?>
                      <a href="<?= getImageUrl("buyer_requests",$request_file); ?>" download>
                        <i class="fa fa-arrow-circle-down"> </i> <?= $request_file; ?>
                      </a>
                      <?php } ?>
                    </div>
                  </td>
                  <td><?= $count_send_offers; ?></td>
                  <td><?= $delivery_time; ?></td>
                  <td class="text-success">
                    <?php if(!empty($request_budget)){ ?>
                    <?= showPrice($request_budget); ?>
                    <?php }else{ ?> ----- <?php } ?>
                    <br>
                    <?php if($login_seller_offers == "0"){ ?>
                    <button class="btn btn-success btn-sm mt-4 send_button_<?= $request_id; ?>" data-toggle="modal" data-target="#quota-finish">
                      <?= $lang['button']['send_an_offer']; ?>
                    </button>
                    <?php }else{ ?>
                    <button class="btn btn-success btn-sm mt-4 send_button_<?= $request_id; ?>">
                      <?= $lang['button']['send_offer']; ?>
                    </button>
                    <?php } ?>
                  </td>
                  <?php if($login_seller_offers == "0"){ ?>
                  <?php }else{ ?>
                  <script type="text/javascript">
                    $(".send_button_<?= $request_id; ?>").click(function(){
                     request_id = "<?= $request_id; ?>";
                      $.ajax({
                       method: "POST",
                         url: "requests/send_offer_modal",
                           data: {request_id: request_id }
                        }).done(function(data){
                           $(".append-modal").html(data);
                        });
                      });
                     <?php } ?>
                  </script>
                </tr>
                <?php } } ?>
              </tbody>
            </table>
            <?php
              if($requests_count == 0){
                  echo "<div class='text-center py-5 bg-light'><p class='text-muted lead mb-0'><i class='fa fa-frown-o'></i> {$lang['user_home']['no_recent_requests']}</p></div>";
              } else {
            ?>
            <center>
              <a href="requests/buyer_requests" class="btn btn-success btn-lg mb-3">
                <i class="fa fa-spinner"></i> <?= $lang['button']['load_more']; ?>
              </a>
            </center>
            <?php } ?>
            </div>
          </div>
          </div>
        </div>
      </div>
      <?php }} ?>
    </div>
  </div>
</div>
<!-- Container ends -->
<br>
<div class="append-modal"></div>
<div id="quota-finish" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title h5"><i class="fa fa-frown-o fa-move-up"></i> <?= $lang['requests']['quota_reached']; ?></h5>
        <button class="close" data-dismiss="modal"> &times; </button>
      </div>
      <div class="modal-body">
        <center>
        <h5><?= $lang['requests']['max_offers_per_day']; ?></h5>
        </center>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal"><?= $lang['button']['close']; ?></button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){

  // $(".carousel-indicators").css({"bottom": "75px"});

  var slider = $('#demo3').carousel({
    interval: 4000
  });

  var active = $(".carousel-item.active").find("video");
  var active_length = active.length;

  if(active_length == 1){
    slider.carousel('pause');
    $(".carousel-indicators").css({"bottom": "75px"});
  }

  $("#demo3").on('slide.bs.carousel', function(event){
    var eq = event.to;
    var video = $(event.relatedTarget).find("video");
    if(video.length == 1){
      slider.carousel('pause');
      $(".carousel-indicators").css({"bottom": "75px"});
      video.trigger('play');
    }else{
      $(".carousel-indicators").css({"bottom": "20px"});
    }
  });

  $('video').on('ended',function(){
    slider.carousel({'pause': false});
  });

});

</script>