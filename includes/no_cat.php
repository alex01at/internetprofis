<?php
session_start(); // Starte die Sitzung, falls noch nicht geschehen

if(isset($_SESSION['cat_id'])){
    $cat_id = $_SESSION['cat_id'];

    // Abrufen der Kategorie-URL aus der Datenbank
    $get_cat_url = $db->select("categories", array("cat_id" => $cat_id));
    $row_cat_url = $get_cat_url->fetch();
    $cat_url = $row_cat_url->cat_url;

    $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
    $row_meta = $get_meta->fetch();
    $cat_title = $row_meta->cat_title;
    $cat_desc = $row_meta->cat_desc;
?>

</div><div class="card-columns">
<?php
}

$get_child_cat = $db->select("categories_children", array("child_parent_id" => $cat_id));
while($row_child_cat = $get_child_cat->fetch()){
    $child_id = $row_child_cat->child_id;
    $child_url = $row_child_cat->child_url;
    $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
    $row_meta = $get_meta->fetch();
    $child_title = $row_meta->child_title;
    $child_desc = $row_meta->child_desc;
    if(!empty($child_title)){
?>
<div class="card">
    <div class="card-header">
        <?= $child_title; ?>
    </div>
    <div class="card-body">
        <p class="card-text"><?= $child_desc; ?></p>
        <a href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>" class="btn btn-primary"><?= $child_title; ?></a>
    </div>
</div>

<?php 
    }
} 
?>
</div>

<div class='col-md-12'>
    <h1 class='text-center mt-4'><i class='fa fa-meh-o'></i> <?= $lang['category']['no_results']; ?> </h1><br>
</div>
