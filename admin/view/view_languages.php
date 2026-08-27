<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
?>

<div class="main-container">
    <div class="row">
        <!--- 2 row Starts --->
        <div class="col-lg-12">
            <!--- col-lg-12 Starts --->
            <div class="card">
                <!--- card Starts --->
                <div class="card-header">
                <ol class="text-right">
                            <li class="active"><a href="index?insert_language" class="btn btn-success">
                                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Language</span>
                                        </a></li>
                        </ol>
                    <!--- card-header Starts --->
                    <h4 class="h4">
                        <i class="fa fa-money-bill-alt"></i> View All Languages
                    </h4>
                </div>
                <!--- card-header Ends --->
                <div class="card-body">
                    <!--- card-body Starts --->
				<table class="table table-bordered table-striped"><!--- table table-bordered table-striped Starts -->
				<thead>
				<tr>
				<th>No</th>
				<th>Title</th>
				<th>Actions:</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
                $get_languages = $db->select("languages","");
                while($row_languages = $get_languages->fetch()){
                $id = $row_languages->id;
                $title = $row_languages->title;
				$i++;
				?>
				<tr>
				<td><?= $i; ?></td>
				<td width="200"><?= $title; ?></td>
				<td>
				<a class="btn text-white btn-success" href="index?language_settings=<?= $id; ?>" >
					<i class="fa fa-fw fa-cog"></i> Settings
				</a>
				<a class="btn text-white btn-primary" href="index?edit_language=<?= $id; ?>" >
					<i class="fa fa-trash-alt"></i> Edit
				</a>
                <?php if($title != "English"){ ?>
				<a class="btn text-white btn-danger" href="index?delete_language=<?= $id; ?>" onclick="if(!confirm('Are you sure you want to delete selected item.')){ return false; }">
				<i class="fa fa-trash-alt"></i> Delete
				</a>
                <?php }else{ ?>
                <button type="button" class="btn btn-danger" disabled>Delete</button>
                <?php } ?>
				</td>
				</tr>
				<?php } ?>
				</tbody>
				</table><!--- table table-bordered table-hover table-striped Starts -->
                </div>
                <?php 
                $get_general_settings = $db->select("general_settings");   
$language_switcher = $row_general_settings->language_switcher;
$row_general_settings = $get_general_settings->fetch();
$enable_google_translate = $row_general_settings->enable_google_translate;

                ?>
<form method="post" enctype="multipart/form-data"><!--- form Starts --->

                <div class="row"><!--- form-group row Starts --->
<label class="col-md-3"> Show Language Switcher: (global setting)</label>
<div class="col-md-6">
  <div class="input-group">
  <span class="input-group-addon"><b><i class="fa fa-link"></i></b></span>
  <select name="language_switcher" class="form-control" required="">
    <option value="1" <?php if($language_switcher == 1){ echo "selected"; } ?>> Yes </option>
    <option value="0" <?php if($language_switcher == 0){ echo "selected"; } ?>> No </option>
  </select>
  </div>
</div>
</div>
<div class="form-group row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Enable Google Translater: <div style="color: red;">use it carefully</div></label>
<div class="col-md-6">
  <div class="input-group">
  <span class="input-group-addon"><b><i class="fa fa-google"></i></b></span>
  <select name="enable_google_translate" class="form-control" required="">
    <option value="1" <?php if($enable_google_translate == 1){ echo "selected"; } ?>> Yes </option>
    <option value="0" <?php if($enable_google_translate == 0){ echo "selected"; } ?>> No </option>
  </select>
  </div>
</div>
</div><!--- form-group row Ends --->
<div class="form-group row">
<!--- form-group row Starts --->
<label class="col-md-3 control-label"></label>
<div class="col-md-6">
<input type="submit" name="save_switcher" class="form-control btn btn-success" value="Save Settings">
</div>
</div>
</form>
<?php 
if(isset($_POST['save_switcher'])){
	$language_switcher = $input->post('language_switcher');
    $enable_google_translate = $input->post('enable_google_translate');
    $update_general_settings = $db->update("general_settings",array(
        "language_switcher" => $language_switcher,
        "enable_google_translate" => $enable_google_translate
    ));
    if($update_general_settings){
        $insert_log = $db->insert_log($admin_id,"general_settings","","updated");
        
            echo "<script>alert_success('Settings has been updated successfully.','index?view_languages');</script>";
    }
}

?>
                <!--- card-body Ends --->
            </div>
            <!--- card Ends --->
        </div>
        <!--- col-lg-12 Ends --->
    </div>
    <!--- 2 row Ends --->
</div>
<?php } ?>
