
<?php 
include('general_settings.php');
?>
<div class="main-container">
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left text-info font-30">Some fields would be use to build your imprint!
        </div>
    </div>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label"> Site Title :</label>
            <div class="col-sm-12 col-md-5">
                <input type="text" name="site_title" class="form-control" value="<?= $site_title; ?>" required="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Site Description:</label>
            <div class="col-sm-12 col-md-5">
                <textarea name="site_desc" class="form-control" rows="5" maxlength="160"
                    id="limited_text"><?= $site_desc; ?></textarea>
                <span id="remaining_chars"><strong>160</strong> characters remaining</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Site Keywords:</label>
            <div class="col-sm-12 col-md-5">
                <input type="text" name="site_keywords" class="form-control" value="<?= $site_keywords; ?>">
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Site Author:</label>
            <div class="col-sm-12 col-md-5">
                <input type="text" name="site_author" class="form-control" value="<?= $site_author; ?>" required="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Site Name:</label>
            <div class="col-sm-12 col-md-5">
                <input type="text" name="site_name" class="form-control" value="<?= $site_name; ?>" required="">
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label">Site E-mail Address: </label>
            <div class="col-sm-12 col-md-5">
                <input type="text" name="site_email_address" class="form-control" value="<?= $site_email_address; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-3 col-form-label"> Copyright Text :</label>
            <div class="col-sm-12 col-md-5">
                <input type="text" name="site_copyright" class="form-control" value="<?= $site_copyright; ?>">
            </div>
        </div>
        <div>
            <input type="submit" name="seo_update" class="form-control btn btn-success" value="Update SEO Settings">
        </div>
    </form>
</div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const textField = document.getElementById("limited_text");
        const remainingChars = document.getElementById("remaining_chars");
        textField.addEventListener("input", function () {
            const maxLength = parseInt(textField.getAttribute("maxlength"));
            const currentLength = textField.value.length;
            const remaining = maxLength - currentLength;
            if (remaining >= 0) {
                remainingChars.innerHTML = `<strong>${remaining}</strong> characters remaining`;
            } else {
                remainingChars.textContent = "Limit exceeded";
            }
        });
    });
</script>
<?php
if(isset($_POST['seo_update'])){
    $site_title = $input->post('site_title');
    $site_name = $input->post('site_name');
	$site_desc = $input->post('site_desc');
	$site_keywords = $input->post('site_keywords');
	$site_author = $input->post('site_author');
  $site_copyright = $input->post('site_copyright');
  $site_email_address = $input->post ('site_email_address');
		$SEO_general_settings = $db->update("general_settings",array(
      "site_title" => $site_title,    
      "site_name" => $site_name,  
      "site_desc" => $site_desc,
      "site_keywords" => $site_keywords,
      "site_author" => $site_author,
      "site_copyright" => $site_copyright,
      "site_email_address" => $site_email_address
    ));
		if($SEO_general_settings){
			$insert_log = $db->insert_log($admin_id,"general_settings","","updated");
				echo "<script>alert_success('SEO Settings has been updated successfully.','index?seo');</script>";
    } 
	}

?>