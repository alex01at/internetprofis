<?php 
include('general_settings.php');
?>
<div class="main-container">
<div class="pd-20 card-box mb-30">
   
    <form method="post" enctype="multipart/form-data">
        <!-- Site WWW -->
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label"> Enable Site (WWW) :</label>
            <div class="col-sm-12 col-md-5">
                <select class="custom-select col-12" name="site_www">
                    <option value="">Choose...</option>
                    <option value="1" <?= ($site_www == 1) ? "selected" : ""; ?>> Yes </option>
                    <option value="0" <?= ($site_www == 0) ? "selected" : ""; ?>> No </option>
                </select>
            </div>
        </div>

        <!-- Site URL -->
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Site URL:</label>
            <div class="col-sm-12 col-md-5">
                <input type="url" name="site_url" class="form-control" value="<?= htmlspecialchars($site_url); ?>" required="">
                <small class="form-text text-muted">
                    <span>NB: Enter the complete url. Ex: https://www.GigToDo.net</span>
                </small>
            </div>
        </div>

        <!-- Site Email -->
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Site Email:</label>
            <div class="col-sm-12 col-md-5">
                <input class="form-control" value="<?= htmlspecialchars($site_email_address); ?>" name="site_email_address" type="email">
            </div>
        </div>
      
        <!-- Timezone -->
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Site Timezone:</label>
            <div class="col-sm-12 col-md-5">
                <select name="site_timezone" class="custom-select col-12">
                    <?php foreach ($timezones as $zone) { ?>
                        <option <?= ($site_timezone == $zone) ? "selected" : ""; ?> value="<?= $zone; ?>"><?= $zone; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <!-- Maintenance Mode -->
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Enable Maintenance Mode: </label>
            <div class="col-sm-12 col-md-5">
                <select name="enable_maintenance_mode" class="form-control" required="">
                    <option value="yes" <?= ($enable_maintenance_mode == "yes") ? "selected" : ""; ?>> Yes </option>
                    <option value="no" <?= ($enable_maintenance_mode == "no") ? "selected" : ""; ?>> No </option>
                </select>
            </div>
        </div>

        <!-- Cookie Notice (KORRIGIERT) -->
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Enable Cookie notice: </label>
            <div class="col-sm-12 col-md-5">
                <select name="enable_cookie_notice" class="form-control" required="">
                    <option value="yes" <?= ($enable_cookie_notice == "yes") ? "selected" : ""; ?>> Yes </option>
                    <option value="no" <?= ($enable_cookie_notice == "no") ? "selected" : ""; ?>> No </option>
                </select>
            </div>
        </div>

        <!-- Currency Position (KORRIGIERT) -->
        <div class="form-group row">
            <label class="col-md-3 control-label"> Currency Symbol Position : </label>
            <div class="col-md-5">
                <select name="currency_position" class="form-control">
                    <option value="left" <?= ($currency_position == "left") ? "selected" : ""; ?>> Left </option>
                    <option value="right" <?= ($currency_position == "right") ? "selected" : ""; ?>> Right </option>
                </select>
            </div>
        </div>

        <div>
            <input type="submit" name="general_settings_update" class="form-control btn btn-success" value="Update General Settings">
        </div>
    </form>
</div>
</div>

<?php 
if (isset($_POST['general_settings_update'])) {
    // POST-Daten abfangen (inklusive der fehlenden Felder!)
    $site_www                = $input->post('site_www');
    $site_url                = $input->post('site_url');
    $site_email_address      = $input->post('site_email_address');
    $site_timezone           = $input->post('site_timezone');
    $enable_maintenance_mode = $input->post('enable_maintenance_mode');
    $enable_cookie_notice    = $input->post('enable_cookie_notice'); // HIER GELADEN
    $currency_position       = $input->post('currency_position');    // HIER GELADEN

    $update_general_settings = $db->update("general_settings", array(
        "site_www"                 => $site_www,
        "site_url"                 => $site_url,
        "site_email_address"       => $site_email_address,
        "language_switcher"        => $language_switcher,
        "site_timezone"            => $site_timezone,
        "enable_cookie_notice"     => $enable_cookie_notice,
        "enable_maintenance_mode"  => $enable_maintenance_mode,
        "currency_position"        => $currency_position // In DB schreiben nicht vergessen
    ));

    if ($update_general_settings) {
        $insert_log = $db->insert_log($admin_id, "general_settings", "", "updated");
        if (updateHtaccess($site_www)) {
            echo "<script>alert_success('General Settings have been updated successfully.', 'index?general');</script>";
        }
    }
}
?>