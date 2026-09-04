<?php
/**
 * Homepage - renders configurable sections (Admin -> Settings -> Theme
 * Settings -> Homepage Layout) in their stored order.
 */

$get_layout_blocks = $db->query(
    "SELECT * FROM home_layout_blocks WHERE language_id = :lang AND enabled = 'yes' ORDER BY position ASC",
    ["lang" => $siteLanguage]
);
$layout_blocks = $get_layout_blocks ? $get_layout_blocks->fetchAll() : [];

foreach($layout_blocks as $block){
    $block_id = $block->id;
    $partial = __DIR__."/includes/home_blocks/".$block->block_type.".php";
    if(is_file($partial)){
        require($partial);
    }
}
?>
