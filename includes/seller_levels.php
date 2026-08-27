<?php
$seller_user_name = $_SESSION['seller_user_name'];
$get_seller = $db->select("sellers", ["seller_user_name" => $seller_user_name]);
$row_seller = $get_seller->fetch();
$seller_id = $row_seller->seller_id;
$seller_level = $row_seller->seller_level;
$seller_rating = $row_seller->seller_rating;

// Abgeschlossene Bestellungen zählen
$count_orders = $db->count("orders", ["seller_id" => $seller_id, "order_status" => 'completed']);

// Einstellungen laden
$row_settings = $db->select("general_settings")->fetch();

/** 
 * Konfiguration der Level-Stufen
 * Format: Aktuelles Level => [Ziel-Level, Nötiges Rating, Nötige Orders, Modal-ID, Titel, Badge, Text]
 */
$levels = [
    1 => [2, $row_settings->level_one_rating, $row_settings->level_one_orders, "level-one-modal", "Promoted To Level One", "level_badge_1.png", "Great", "You're now a level one seller."],
    2 => [3, $row_settings->level_two_rating, $row_settings->level_two_orders, "level-two-modal", "Promoted To Level Two", "level_badge_2.png", "Awesome", "You're now a level 2 seller. Good Job!"],
    3 => [4, $row_settings->level_top_rating, $row_settings->level_top_orders, "top-rated-modal", "Top Rated Seller", "level_badge_3.png", "Splendid", "You're Now a Top Rated Seller. More Customers Will Trust You!"]
];

// Prüfen, ob für das aktuelle Level eine Beförderungs-Logik existiert
if (isset($levels[$seller_level])) {
    list($next_level, $req_rating, $req_orders, $modal_id, $modal_title, $badge, $headline, $message) = $levels[$seller_level];

    // Bedingung prüfen
    if ($seller_rating >= $req_rating && $count_orders >= $req_orders) {
        
        // Datenbank-Updates
        $db->update("sellers", ["seller_level" => $next_level], ["seller_id" => $seller_id]);
        $update_proposals = $db->update("proposals", ["level_id" => $next_level], ["proposal_seller_id" => $seller_id]);

        if ($update_proposals) {
            ?>
            <!-- Einziges Modal-Template für alle Level -->
            <div id="<?= $modal_id; ?>" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"> <?= $modal_title; ?> </h5>
                            <button class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body text-center">
                            <h2> <?= $headline; ?> </h2>
                            <p class="lead"><?= $message; ?></p>
                            <img src="<?= $site_url; ?>/images/<?= $badge; ?>">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function(){ $("#<?= $modal_id; ?>").modal('show'); });
            </script>
            <?php
        }
    }
}
?>