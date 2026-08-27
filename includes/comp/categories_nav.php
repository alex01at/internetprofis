<?php
/**
 * desktop_category_nav.php - Optimierte Version
 * Reduziert Datenbank-Abfragen und nutzt vorhandene Header-Variablen
 */

// 1. Sprache bestimmen (Nutzt Fallback aus Header falls vorhanden)
$current_site_lang = $_SESSION['siteLanguage'] ?? ($current_site_lang ?? 1);

// 2. Haupt-Kategorien holen
$get_categories = $db->query("
    SELECT c.cat_id, c.cat_url, m.cat_title 
    FROM categories c 
    INNER JOIN cats_meta m ON c.cat_id = m.cat_id 
    WHERE c.cat_featured = 'yes' 
    AND m.language_id = :lang
    GROUP BY c.cat_id 
    ORDER BY " . (($lang_dir ?? 'left') == "right" ? "c.cat_id DESC" : "c.cat_id ASC"),
    array("lang" => $current_site_lang)
);

$all_categories = $get_categories->fetchAll();

// Fallback auf Englisch, falls keine Kategorien in der aktuellen Sprache gefunden wurden
if(empty($all_categories) && $current_site_lang != 1){
    $get_categories = $db->query("
        SELECT c.cat_id, c.cat_url, m.cat_title FROM categories c 
        INNER JOIN cats_meta m ON c.cat_id = m.cat_id 
        WHERE c.cat_featured = 'yes' AND m.language_id = 1
        GROUP BY c.cat_id ORDER BY c.cat_id ASC"
    );
    $all_categories = $get_categories->fetchAll();
}

if(!empty($all_categories)):
    $main_categories = array_slice($all_categories, 0, 9);
    $more_categories_list = array_slice($all_categories, 9);
    $main_category_ids = array_column($main_categories, 'cat_id');

    // --- OPTIMIERUNG: Alle Unterkategorien für die Hauptkategorien auf einmal holen ---
    $children_by_parent = [];
    if(!empty($main_category_ids)){
        $ids_placeholder = implode(',', $main_category_ids);
        $get_all_children = $db->query("
            SELECT cc.child_id, cc.child_parent_id, cc.child_url, cm.child_title 
            FROM categories_children cc 
            INNER JOIN child_cats_meta cm ON cc.child_id = cm.child_id 
            WHERE cc.child_parent_id IN ($ids_placeholder)
            AND cm.language_id = :lang
            GROUP BY cc.child_id",
            array("lang" => $current_site_lang)
        );
        
        while($child = $get_all_children->fetch()){
            $children_by_parent[$child->child_parent_id][] = $child;
        }
    }
?>

<div data-ui="cat-nav" id="desktop-category-nav" class="ui-toolkit cat-nav" style="background-color: <?= $navbar_color ?? '#28a745'; ?>;">
  <div class="bg-transparent-homepage-experiment hide-xs hide-sm hide-md">
    <div class="col-group body-max-width">
      <ul class="col-xs-12 body-max-width display-flex-xs justify-content-space-between" data-ui="top-nav-category-list">
        
        <?php foreach($main_categories as $row_cat): ?>
        <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" data-ui="top-nav-category-link" data-node-id="c-<?= $row_cat->cat_id; ?>">
          <a href="<?= $site_url; ?>/categories/<?= $row_cat->cat_url; ?>"><?= htmlspecialchars($row_cat->cat_title); ?></a>
        </li>
        <?php endforeach; ?>

        <?php if(count($all_categories) > 9): ?>
        <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" data-node-id="c-more">
          <a href="#"><?= $lang['more'] ?? 'Mehr'; ?></a>
        </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>

  <div class="position-absolute col-xs-12 col-centered z-index-4">
    <div>
      <?php 
      foreach($main_categories as $row_cat): 
        $cat_id = $row_cat->cat_id;
        $current_children = $children_by_parent[$cat_id] ?? [];

        if(!empty($current_children)):
      ?>
      <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden bl-xs-1 bb-xs-1 br-xs-1 catnav-mott-control display-none" data-ui="sub-nav" aria-hidden="true" data-node-id="c-<?= $cat_id; ?>">
        <div class="width-full display-flex-xs">
          <?php 
          $child_chunks = array_chunk($current_children, 10);
          foreach(array_slice($child_chunks, 0, 4) as $current_chunk): 
          ?>
          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
            <?php foreach($current_chunk as $row_child): ?>
            <li>
              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $row_cat->cat_url; ?>/<?= $row_child->child_url; ?>">
                <?= htmlspecialchars($row_child->child_title); ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; endforeach; ?>

      <?php if(count($all_categories) > 9): ?>
      <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden bl-xs-1 bb-xs-1 br-xs-1 catnav-mott-control display-none" data-ui="sub-nav" aria-hidden="true" data-node-id="c-more">
        <div class="width-full display-flex-xs">
          <?php 
          $more_chunks = array_chunk($more_categories_list, 10);
          foreach(array_slice($more_chunks, 0, 4) as $current_more_chunk): 
          ?>
          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
            <?php foreach($current_more_chunk as $row_more): ?>
            <li>
              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $row_more->cat_url; ?>">
                <?= htmlspecialchars($row_more->cat_title); ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>
<?php endif; ?>
<?php include("mobile_menu.php"); ?>