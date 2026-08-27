<?php
/**
 * Optimierte footer.php
 */
require_once("db.php"); 

// 1. Zentrale Einstellungen
$get_general_settings = $db->select("general_settings");
$row_general_settings = $get_general_settings->fetch();

$footer_color = $row_general_settings->footer_color ?? "#333";
$post_footer_color = $row_general_settings->post_footer_color ?? "#222";
$enable_copyright = $row_general_settings->enable_copyright ?? 0;
$site_copyright = $row_general_settings->site_copyright ?? "";

/** * PERFORMANCE-BOOST: 
 * Wir laden ALLE Footer-Links mit einer einzigen Abfrage statt 4-mal zu fragen.
 * Wir sortieren sie danach einfach in PHP aus.
 */
$all_footer_links = [];
$get_links = $db->select("footer_links", ["language_id" => $siteLanguage]);
while($link = $get_links->fetch()){
    $all_footer_links[$link->link_section][] = $link;
}

// Hilfsfunktion zum sauberen Ausgeben der Listen
function renderFooterSection($links, $site_url = "") {
    if(empty($links)) return;
    foreach($links as $link) {
        $icon = !empty($link->icon_class) ? "<i class='fa " . htmlspecialchars($link->icon_class) . "'></i> " : "";
        $url = (strpos($link->link_url, 'http') === 0) ? $link->link_url : $site_url . "/" . $link->link_url;
        echo "<li class='list-unstyled-item'><a href='" . htmlspecialchars($url) . "'>$icon" . htmlspecialchars($link->link_title) . "</a></li>";
    }
}
?>

<footer class="footer" style="background-color: <?= $footer_color ?>;">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    
                    <div class="col-md-3 col-12">
                        <h3 data-toggle="collapse" data-target="#collapsecategories" aria-expanded="true"><?= $lang['categories']; ?></h3>
                        <ul class="collapse show list-unstyled" id="collapsecategories">
                            <?php renderFooterSection($all_footer_links['categories'] ?? []); ?>
                        </ul>
                    </div>

                    <div class="col-md-3 col-12">
                        <h3 class="h3Border" data-toggle="collapse" data-target="#collapseabout"><?= $lang['about']; ?></h3>
                        <ul class="collapse show list-unstyled" id="collapseabout">
                            <?php renderFooterSection($all_footer_links['about'] ?? [], $site_url); ?>
                        </ul>
                    </div>
                    
                    <div class="col-md-3 col-12">
                        <h3 class="h3Border" data-toggle="collapse" data-target="#collapsepages"><?= $lang['pages']; ?></h3>
                        <ul class="collapse show list-unstyled" id="collapsepages">
                            <?php
                            $pages = $db->query("SELECT p.url, m.title FROM pages p JOIN pages_meta m ON p.id=m.page_id WHERE m.language_id=:lang", ["lang" => $siteLanguage]);
                            while($rowPage = $pages->fetch()){
                                echo "<li class='list-unstyled-item'><a href='$site_url/pages/".htmlspecialchars($rowPage->url)."'>".htmlspecialchars($rowPage->title)."</a></li>";
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="col-md-3 col-12">
                        <h3 data-toggle="collapse" data-target="#collapselegal"><?= $lang['legal']; ?></h3>
                        <ul class="collapse show list-unstyled" id="collapselegal">
                            <?php renderFooterSection($all_footer_links['legal'] ?? []); ?>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="col-md-3 col-12">
                <h3 class="h3Border" data-toggle="collapse" data-target="#collapsefindusOn"><?= $lang['find_us_on']; ?></h3>
                <div class="collapse show" id="collapsefindusOn">
                    <ul class="list-inline social_icon">
                        <?php
                        if(isset($all_footer_links['follow'])):
                            foreach($all_footer_links['follow'] as $rowS): ?>
                                <li class='list-inline-item'>
                                    <a href='<?= htmlspecialchars($rowS->link_url) ?>' target='_blank' aria-label='Follow us'>
                                        <i class='fa <?= htmlspecialchars($rowS->icon_class) ?>'></i>
                                    </a>
                                </li>
                        <?php endforeach; endif; ?>
                    </ul>

                    <div class="form-group mt-0">
                        <?php if($language_switcher == 1): ?>
                            <label for="languageSelect" class="sr-only">Sprache wählen</label>
                            <select id="languageSelect" class="form-control">
                                <?php 
                                $get_languages = $db->select("languages");
                                while($row_l = $get_languages->fetch()){
                                    $selected = ($row_l->id == $_SESSION["siteLanguage"]) ? "selected" : "";
                                    $image = getImageUrl("languages", $row_l->image); 
                                    // HIER wird das Bild für das Plugin übergeben:             
                                    echo "<option data-image='$image' data-url='$site_url/change_language?id={$row_l->id}' $selected>{$row_l->title}</option>";
                                } 
                                ?>
                            </select>
                        <?php endif; ?>

                        <?php if($enable_google_translate == 1): ?>
                            <div id="google_translate_element" class="mt-2"></div>
                        <?php endif; ?>

                        <?php if($enable_converter == 1): ?>
                            <label for="currencySelect2" class="sr-only">Währung wählen</label>
                            <select id="currencySelect2" class="form-control mt-2">
                                <option data-url="<?= "$site_url/change_currency?id=0"; ?>">
                                    <?= "$s_currency_name ($s_currency)"; ?>
                                </option>
                                <?php
                                $currencies = $db->query("SELECT sc.id, c.name, c.symbol FROM site_currencies sc JOIN currencies c ON sc.currency_id=c.id");
                                while($row_c = $currencies->fetch()){
                                    $selected = (isset($_SESSION["siteCurrency"]) && $row_c->id == $_SESSION["siteCurrency"]) ? "selected" : "";
                                    echo "<option data-url='$site_url/change_currency?id={$row_c->id}' $selected>{$row_c->name} ({$row_c->symbol})</option>";
                                } 
                                ?>
                            </select>
                        <?php endif; ?>
                    </div>
                    
                    <?php if(!empty($google_app_link) || !empty($apple_app_link)): ?>
                        <h5><?= $lang['mobile_apps']; ?></h5>
                        <?php if(!empty($google_app_link)): ?>
                            <a href="<?= $google_app_link; ?>" target="_blank"><img src="<?= $site_url; ?>/images/google.png" class="pic" alt="Google Play Store"></a>
                        <?php endif; ?>
                        <?php if(!empty($apple_app_link)): ?>
                            <a href="<?= $apple_app_link; ?>" target="_blank"><img src="<?= $site_url; ?>/images/app.png" class="pic1" alt="Apple App Store"></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <br>
</footer>

<?php if($enable_copyright == 1): ?>
<section class="post_footer" style="background-color: <?= $post_footer_color; ?>;">
    <div class="container text-center">
        <?= $site_copyright; ?>
    </div>
</section>
<?php endif; ?>
<?php if($row_general_settings->enable_cookie_notice == 'yes'): ?>
<?php if(!isset($_COOKIE['close_cookie'])): ?>
<section class="clearfix cookies_footer row animated slideInLeft" role="alert">
    <div class="col-md-4">
        <img src="<?= $site_url; ?>/images/cookie.png" class="img-fluid" alt="Cookie Info">
    </div>
    <div class="col-md-8">
        <div class="float-right close btn btn-sm" aria-label="Schließen"><i class="fa fa-times"></i></div>
        <h4 class="mt-0 mt-lg-2 <?=($lang_dir == "right"?'text-right':'')?>"><?= htmlspecialchars($lang["cookie_box"]['title']); ?></h4>
        <p class="mb-1"><?= str_replace('{link}', "$site_url/terms_and_conditions", $lang["cookie_box"]['desc']); ?></p>
        <a href="#" class="btn btn-success btn-sm"><?= htmlspecialchars($lang["cookie_box"]['button']); ?></a>
    </div>
</section>
<?php endif; ?>
<?php endif; ?>
<?php if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] == "demo@internetprofis.at"): ?>
    <div class="fixed-bottom bg-danger text-white d-flex align-items-center justify-content-center shadow-lg" style="height: 50px; z-index: 9999;">
        <strong>Demo Mode:</strong> Saving, updating or deleting is disabled.
    </div>
    <style>body { padding-bottom: 50px !important; }</style>
<?php endif; ?>

<?php
if(isset($videoPlugin) && $videoPlugin == 1){
    require("$dir/plugins/videoPlugin/footer/videoCall.php"); 
}
require("footerJs.php"); 
?>
<script>
$(document).ready(function() {
    // Wir warten kurz, bis das Plugin das Dropdown fertig gebaut hat
    setTimeout(function() {
        // Wir gehen durch jedes Sprach-Dropdown
        $("#languageSelect option").each(function() {
            var title = $(this).text(); // Z.B. "Deutsch"
            var value = $(this).val(); // Die ID der Sprache
            
            // Suche das Bild im neuen MS-Dropdown, das zu dieser Option gehört
            // Das Plugin baut eine Struktur mit <li> Elementen
            $(".ddChild li").each(function() {
                var text = $(this).find(".ddlabel").text();
                if (text === title) {
                    // Setze den Alt-Tag für das Bild in diesem Listenpunkt
                    $(this).find("img").attr("alt", "Flag " + title);
                }
            });
        });

        // Auch für das aktuell ausgewählte Bild (oben im geschlossenen Dropdown)
        var currentLang = $("#languageSelect option:selected").text();
        $(".ddTitle img").attr("alt", "Selected Flag " + currentLang);
        
    }, 500); // 500ms Verzögerung, damit das Plugin Zeit zum Bauen hat
});
</script>