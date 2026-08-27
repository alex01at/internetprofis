<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login','_self');</script>";
} else {

    // 1. Zähler für die Tabs (Effiziente Abfragen)
    $count_all_proposals = $db->query("SELECT * FROM proposals WHERE proposal_status NOT IN ('modification','draft','deleted')")->rowCount();
    $count_active_proposals = $db->count("proposals", ["proposal_status" => "active"]);
    $count_featured_proposals = $db->count("proposals", ["proposal_status" => "active", "proposal_featured" => "yes"]);
    $count_pending_proposals = $db->count("proposals", ["proposal_status" => "pending"]);
    $count_pause_proposals = $db->query("SELECT * FROM proposals WHERE proposal_status='pause' OR proposal_status='admin_pause'")->rowCount();
    $count_trash_proposals = $db->count("proposals", ["proposal_status" => "trash"]);

    // Seitenzahl bestimmen
    $per_page = 10;
    $page = isset($_GET['view_proposals']) ? intval($_GET['view_proposals']) : (isset($_GET['page']) ? intval($_GET['page']) : 1);
    if($page <= 0) $page = 1;
    $start_from = ($page - 1) * $per_page;
?>

<div class="main-container">
    <div class="row">
        <div class="col-lg-12">
            <div class="p-3 mb-3">
                <h2 class="pb-4">Filter Proposals/Services</h2>
                <form class="form-inline pb-2" method="get" action="filter_proposals.php">
                    <div class="form-group">
                        <label> Category : </label>
                        <select name="cat_id" required class="form-control mb-2 ml-1 mr-sm-2 mb-sm-0">
    <option value=""> Select A Category </option>
    <?php
    $get_categories = $db->select("categories");
    // Hier ist der Fix: Wir stellen sicher, dass $row_categories existiert 
    // und weisen $cat_id explizit zu
    while($row_categories = $get_categories->fetch()){
        $cat_id = $row_categories->cat_id; // Diese Zeile hat wahrscheinlich gefehlt
        
        $get_meta = $db->select("cats_meta", ["cat_id" => $cat_id, "language_id" => $adminLanguage]);
        $row_meta = $get_meta->fetch();
        
        // Null-Check (unser Fehler von vorhin)
        $cat_title = $row_meta ? $row_meta->cat_title : "No Title (ID: $cat_id)";
        
        echo "<option value='$cat_id'>$cat_title</option>";
    }
    ?>
</select>
                    </div>
                    <div class="form-group">
                        <label> Delivery Time: </label>
                        <select name="delivery_id" class="form-control mb-2 ml-1 mr-sm-2 mb-sm-0">
                            <option value=""> Select A Delivery Time </option>
                            <?php
                            $get_delivery_times = $db->select("delivery_times");
                            while($row_delivery_times = $get_delivery_times->fetch()){
                                echo "<option value='{$row_delivery_times->delivery_id}'>{$row_delivery_times->delivery_title}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label> Seller Level: </label>
                        <select name="level_id" class="form-control mb-2 ml-1 mr-sm-2 mb-sm-0">
                            <option value=""> Select A Seller Level </option>
                            <?php
                            $get_seller_levels = $db->select("seller_levels");
                            while($row_seller_levels = $get_seller_levels->fetch()){
                                $level_id = $row_seller_levels->level_id;
                                $level_title = $db->select("seller_levels_meta", ["level_id" => $level_id, "language_id" => $adminLanguage])->fetch()->title;
                                echo "<option value='$level_id'>$level_title</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success"> Filter</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="h4">Proposals</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="index?view_proposals" class="make-black font-weight-bold mr-2">All (<?= $count_all_proposals; ?>)</a> <span class="mr-2">|</span>
                        <a href="index?view_proposals_active" class="mr-2">Active (<?= $count_active_proposals; ?>)</a> <span class="mr-2">|</span>
                        <a href="index?view_proposals_featured" class="mr-2">Featured (<?= $count_featured_proposals; ?>)</a> <span class="mr-2">|</span>
                        <a href="index?view_proposals_pending" class="mr-2">Pending Approval (<?= $count_pending_proposals; ?>)</a> <span class="mr-2">|</span>
                        <a href="index?view_proposals_paused" class="mr-2">Paused (<?= $count_pause_proposals; ?>)</a> <span class="mr-2">|</span>
                        <a href="index?view_proposals_trash" class="mr-2">Trash (<?= $count_trash_proposals; ?>)</a>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Proposal's Title</th>
                                    <th>Proposal's Display Image</th>
                                    <th>Proposal's Price</th>
                                    <th>Proposal's Category</th>
                                    <th>Order Queue</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // OPTIMIERUNG: Ein einziger großer JOIN um Verkäufer und Kategorie-Titel sofort zu haben
                                $get_proposals = $db->query("
                                    SELECT p.*, s.seller_user_name, cm.cat_title 
                                    FROM proposals p 
                                    LEFT JOIN sellers s ON p.proposal_seller_id = s.seller_id 
                                    LEFT JOIN cats_meta cm ON (p.proposal_cat_id = cm.cat_id AND cm.language_id = :adminLang)
                                    WHERE p.proposal_status NOT IN ('modification','draft','deleted') 
                                    ORDER BY p.proposal_id DESC LIMIT :limit OFFSET :offset", 
                                    ["adminLang" => $adminLanguage], 
                                    ["limit" => $per_page, "offset" => $start_from]
                                );

                                while($row_proposals = $get_proposals->fetch()){
                                    $proposal_id = $row_proposals->proposal_id;
                                    $proposal_title = $row_proposals->proposal_title;
                                    $proposal_url = $row_proposals->proposal_url;
                                    $proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
                                    $proposal_status = $row_proposals->proposal_status;
                                    $seller_user_name = $row_proposals->seller_user_name;
                                    $cat_title = $row_proposals->cat_title;
                                    
                                    // Preis-Logik
                                    if($row_proposals->proposal_price == 0){
                                        $p_price = "";
                                        $get_p = $db->select("proposal_packages", ["proposal_id" => $proposal_id]);
                                        while($row_package = $get_p->fetch()){
                                            $p_price .= " | $s_currency" . $row_package->price;
                                        }
                                        $proposal_price = ltrim($p_price, ' |');
                                    } else {
                                        $proposal_price = "$s_currency" . $row_proposals->proposal_price;
                                    }

                                    // Order Queue zählen
                                    $proposal_order_queue = $db->query("SELECT order_id FROM orders WHERE proposal_id='$proposal_id' AND order_status NOT IN ('complete','cancelled')")->rowCount();
                                ?>
                                <tr>
                                    <td><?= $proposal_title; ?></td>
                                    <td><img src="<?= $proposal_img1; ?>" width="70" height="60"></td>
                                    <td><?= $proposal_price; ?></td>
                                    <td><?= $cat_title; ?></td>
                                    <td><?= $proposal_order_queue; ?></td>
                                    <td><span class="badge badge-secondary"><?= ucfirst($proposal_status); ?></span></td>
                                    <td>
                                        <a title="View" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <?php if($proposal_status == "active"){ ?>
                                            <a href="index?<?= ($row_proposals->proposal_featured == "yes" ? "remove_feature_proposal" : "feature_proposal") ?>=<?= $proposal_id; ?>&page=<?= $page; ?>" class="btn btn-sm btn-warning">
                                                <i class="fa <?= ($row_proposals->proposal_featured == "yes" ? "fa-star" : "fa-star-o") ?>"></i>
                                            </a>
                                            <a href="index?pause_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>" class="btn btn-sm btn-secondary"><i class="fa fa-pause"></i></a>
                                            <a href="index?move_to_trash=<?= $proposal_id; ?>&page=<?= $page; ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                        <?php } elseif($proposal_status == "pending"){ ?>
                                            <a href="index?approve_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Approve</a>
                                        <?php } elseif($proposal_status == "trash"){ ?>
                                            <a href="index?restore_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>" class="btn btn-sm btn-primary"><i class="fa fa-reply"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <ul class="pagination">
                            <?php
                            $total_pages = ceil($count_all_proposals / $per_page);
                            echo "<li class='page-item'><a href='index?view_proposals=1' class='page-link'>First</a></li>";
                            
                            for ($i = max(1, $page - 3); $i <= min($page + 3, $total_pages); $i++) {
                                $active = ($i == $page) ? "active" : "";
                                echo "<li class='page-item $active'><a href='index?view_proposals=$i' class='page-link'>$i</a></li>";
                            }
                            
                            echo "<li class='page-item'><a href='index?view_proposals=$total_pages' class='page-link'>Last</a></li>";
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>