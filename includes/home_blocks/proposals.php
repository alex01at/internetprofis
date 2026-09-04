<section class="top mb-0">
  <div class="container" style="max-width: 1360px !important;">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center"><?= $lang['home']['proposals']['title']; ?></h1>
        <h4 class="text-center"><?= $lang['home']['proposals']['desc']; ?></h4>

        <?php
        // Proposals holen - JOIN mit Sellers spart massiv Queries
        $get_proposals = $db->query("
            SELECT p.*, s.seller_user_name, s.seller_image, s.seller_level, s.seller_status
            FROM proposals p
            INNER JOIN sellers s ON p.proposal_seller_id = s.seller_id
            WHERE p.proposal_featured = 'yes' AND p.proposal_status = 'active'
            LIMIT 10");
        ?>

        <div class="mt-5">
          <div class="row">
            <?php
            while($row_proposals = $get_proposals->fetch()):
              $proposal_id = $row_proposals->proposal_id;

              // Preis-Logik optimiert
              $proposal_price = $row_proposals->proposal_price;
              if($proposal_price == 0){
                $get_p_1 = $db->select("proposal_packages", ["proposal_id" => $proposal_id, "package_name" => "Basic"]);
                $proposal_price = $get_p_1->fetch()->price ?? 0;
              }

              // Verkäufer Daten (bereits im JOIN geladen!)
              $seller_user_name = $row_proposals->seller_user_name;
              $seller_image = getImageUrl2("sellers", "seller_image", $row_proposals->seller_image);
              if(empty($row_proposals->seller_image)) $seller_image = "empty-image.png";

              // Rating-Logik (Könnte man noch in SQL lösen, hier erst mal bereinigt)
              $select_buyer_reviews = $db->select("buyer_reviews", ["proposal_id" => $proposal_id]);
              $count_reviews = $select_buyer_reviews->rowCount();
              $average_rating = 0;
              if($count_reviews > 0){
                $total_rating = 0;
                while($review = $select_buyer_reviews->fetch()) { $total_rating += $review->buyer_rating; }
                $average_rating = $total_rating / $count_reviews;
              }
            ?>
            <div class="col-xl-2dot4 col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-4">
              <?php require(__DIR__."/../proposals.php"); ?>
            </div>
            <?php endwhile; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
