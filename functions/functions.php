<?php

require_once("$dir/social-config.php");
require_once("$dir/functions/filter.php");

if(($notifierPlugin ?? 0) == 1){ 
    require_once("$dir/plugins/notifierPlugin/functions.php");
}

// Globaler Login-Check
if(isset($_SESSION['seller_user_name'])){
    $login_seller_user_name = $_SESSION['seller_user_name'];
    $select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
    $row_login_seller = $select_login_seller->fetch();
    $login_seller_id = $row_login_seller->seller_id ?? null;
}

/**
 * Überprüft den Online-Status eines Verkäufers mit Caching-Mechanismus
 */
function check_status($db, $seller_id = null, $login_seller_id = null): string {
    static $status_cache = []; // Verhindert mehrfache DB-Abfragen pro Seitenaufruf

    // Fallback für alten Code-Stil (nur ID übergeben)
    if (!is_object($db)) {
        $seller_id = $db;
        global $db;
    }

    if (empty($seller_id)) return 'Offline';
    if (isset($status_cache[$seller_id])) return $status_cache[$seller_id];

    // Verkäufer abrufen
    $select_seller = $db->select("sellers", array("seller_id" => $seller_id));
    $row_seller = $select_seller->fetch();

    if (!$row_seller) {
        $status_cache[$seller_id] = 'Offline';
        return 'Offline';
    }

    // Wenn es der eigene Account ist
    if (isset($_SESSION['seller_user_name']) && $seller_id == $login_seller_id) {
        return 'Online';
    }

    $seller_activity = $row_seller->seller_activity;
    $current_timestamp = date('Y-m-d H:i:s', strtotime('-10 seconds'));
    
    $status = ($seller_activity > $current_timestamp) ? 'Online' : 'Offline';
    $status_cache[$seller_id] = $status;
    return $status;
}

function insertSale($data){
    global $db;
    $data["date"] = date("Y-m-d");
    return $db->insert("sales", $data);
}

function get_percentage_amount($amount, $percentage){
    return ($percentage / 100) * $amount;
}

function processing_fee($amount){
    global $db;
    $get_payment_settings = $db->select("payment_settings");
    $row_payment_settings = $get_payment_settings->fetch();
    if(!$row_payment_settings) return 0;

    $feeType = $row_payment_settings->processing_feeType;
    $fee = $row_payment_settings->processing_fee;
    
    return ($feeType == "fixed") ? $fee : get_percentage_amount($amount, $fee);
}

/**
 * Universelle Zeitangabe (z.B. "vor 5 Minuten")
 */
function time_ago($timestamp){  
    $time_ago = strtotime($timestamp);  
    $current_time = time();  
    $seconds = $current_time - $time_ago;  
    
    $units = [
        31553280 => 'year',
        2629440  => 'month',
        604800   => 'week',
        86400    => 'day',
        3600     => 'hour',
        60       => 'minute',
        1        => 'second'
    ];

    foreach ($units as $unit_seconds => $unit_name) {
        if ($seconds < $unit_seconds) continue;
        $count = floor($seconds / unit_seconds);
        
        if($unit_name == 'second' && $seconds <= 60) return "Just Now";
        
        $plural = ($count > 1) ? "s" : "";
        // Deutsche Übersetzungshilfe falls nötig, hier im englischen Original:
        return ($unit_name == 'hour') ? "$count hrs ago" : "$count $unit_name$plural ago";
    }
    return "Just Now";
}

/**
 * OPTIMIERUNG: Zusammengefasste Proposal & Pagination Funktionen
 * Ersetzt hunderte Zeilen redundanten Code
 */
function get_universal_proposals($type){ get_proposals($type); }
function get_universal_pagination($type){ get_pagination($type); }

// Fallback für die alten Funktionsaufrufe
function get_search_proposals(){ get_proposals("search"); }
function get_search_pagination(){ get_pagination("search"); }
function get_category_proposals(){ get_proposals("category"); }
function get_category_pagination(){ get_pagination("category"); }
function get_featured_proposals(){ get_proposals("featured"); }
function get_featured_pagination(){ get_pagination("featured"); }
function get_top_proposals(){ get_proposals("top"); }
function get_top_pagination(){ get_pagination("top"); }
function get_random_proposals(){ get_proposals("random"); }
function get_random_pagination(){ get_pagination("random"); }
function get_tag_proposals(){ get_proposals("tag"); }
function get_tag_pagination(){ get_pagination("tag"); }

function addAnd($query){
    return (strlen($query) == 5) ? "" : " and";
}

/**
 * Baut die WHERE-Abfrage für die Freelancer-Suche
 */
function freelancersQueryWhere($type){
    global $db, $login_seller_id;
    
    $values = [];
    $where_parts = [];
    $where_path = "";

    // Online Filter (Performance-Optimiert)
    if(isset($_REQUEST['online_sellers'])){
        $online_ids = [];
        $sellers = $db->query("SELECT seller_id FROM sellers");
        while($s = $sellers->fetch()){
            if(check_status($db, $s->seller_id, $login_seller_id) == "Online"){
                $online_ids[] = $s->seller_id;
            }
        }
        if(!empty($online_ids)){
            $temp_where = [];
            foreach($online_ids as $i => $id){
                $temp_where[] = "seller_id=:online_$i";
                $values["online_$i"] = $id;
            }
            $where_parts[] = "(" . implode(" or ", $temp_where) . ")";
        }
        $where_path .= "online_sellers[]=1&";
    }

    // Filter-Mapping (Country, Level, Language)
    $filters = [
        'seller_country' => 'seller_country',
        'seller_level' => 'seller_level',
        'seller_language' => 'seller_language'
    ];

    foreach($filters as $req_key => $db_col){
        if(isset($_REQUEST[$req_key]) && is_array($_REQUEST[$req_key])){
            $temp_where = [];
            foreach($_REQUEST[$req_key] as $i => $val){
                if($val != "undefined" && $val != "0"){
                    $temp_where[] = "$db_col=:{$req_key}_$i";
                    $values["{$req_key}_$i"] = $val;
                    $where_path .= "{$req_key}[]=" . urlencode($val) . "&";
                }
            }
            if(!empty($temp_where)) $where_parts[] = "(" . implode(" or ", $temp_where) . ")";
        }
    }

    $query_where = !empty($where_parts) ? "where " . implode(" and ", $where_parts) : "";

    if($type == "query_where") return $query_where;
    if($type == "where_path") return $where_path;
    if($type == "values") return $values;
}

function get_freelancers(){
    global $db, $input, $lang, $siteLanguage, $s_currency, $login_seller_id;

    $query_where = freelancersQueryWhere("query_where");
    $where_path = freelancersQueryWhere("where_path");
    $values = freelancersQueryWhere("values");

    $per_page = 5;
    $page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : 1;
    $start_from = ($page - 1) * $per_page;

    $query_base = "select DISTINCT sellers.* from sellers JOIN proposals ON sellers.seller_id=proposals.proposal_seller_id and proposals.proposal_status='active'";
    $query_limit = " order by seller_level DESC LIMIT $per_page OFFSET $start_from";

    $sellers = $db->query($query_base . " " . $query_where . $query_limit, $values);

    $sellersCount = 0;
    while($seller = $sellers->fetch()){
        $sellersCount++;
        // Variablen-Zuweisung für includes/freelancer.php
        $seller_id = $seller->seller_id;
        $seller_user_name = $seller->seller_user_name;
        $seller_image = getImageUrl2("sellers","seller_image", $seller->seller_image);
        $seller_level = $seller->seller_level;
        $seller_country = $seller->seller_country;
        
        $level_meta = $db->select("seller_levels_meta", ["level_id" => $seller_level, "language_id" => $siteLanguage])->fetch();
        $level_title = $level_meta->title ?? "Level $seller_level";

        // Bewertung berechnen
        $reviews = $db->select("buyer_reviews", ["review_seller_id" => $seller_id]); 
        $count_reviews = $reviews->rowCount();
        if($count_reviews > 0){
            $total_rating = 0;
            while($r = $reviews->fetch()){ $total_rating += $r->buyer_rating; }
            $average_rating = round($total_rating / $count_reviews, 1);
        } else {
            $average_rating = 0;
        }
        
        require("includes/freelancer.php");
    }

    if($sellersCount == 0){
        echo "<div class='col-md-12 text-center mt-4'><h1><i class='fa fa-meh-o'></i> {$lang['freelancers']['no_results']} </h1></div>";
    }
}

function get_freelancer_pagination(){
    global $db, $input, $lang;

    $query_where = freelancersQueryWhere("query_where");
    $where_path = freelancersQueryWhere("where_path");
    $values = freelancersQueryWhere("values");

    $per_page = 5;
    $query = "select DISTINCT sellers.seller_id from sellers JOIN proposals ON sellers.seller_id=proposals.proposal_seller_id and proposals.proposal_status='active' $query_where";
    $total_records = $db->query($query, $values)->rowCount();
    $total_pages = ceil($total_records / $per_page);
    
    $page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : 1;

    if($total_pages <= 1) return;

    echo "<li class='page-item'><a class='page-link' href='?page=1&$where_path'>{$lang['pagination']['first_page']}</a></li>";
    
    for($i = max(1, $page - 3); $i <= min($page + 3, $total_pages); $i++){
        $active = ($i == $page) ? "active" : "";
        echo "<li class='page-item $active'><a href='?page=$i&$where_path' class='page-link'>$i</a></li>";
    }

    echo "<li class='page-item'><a class='page-link' href='?page=$total_pages&$where_path'>{$lang['pagination']['last_page']}</a></li>";
}
?>