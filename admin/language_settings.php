<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
if(isset($_GET['language_settings'])){
$edit_id = $input->get('language_settings');
$edit_language = $db->select("languages",array('id' => $edit_id));
$row_edit = $edit_language->fetch();
$id = $row_edit->id;
$title = $row_edit->title;
$file = strtolower($title);
$file = "../languages/$file.php";
}
?>
<div class="main-container">
    <div class="row">
        <!--- 2 row Starts --->
        <div class="col-lg-12">
            <!--- col-lg-12 Starts --->
            <div class="card">
                <!--- card Starts --->
                <div class="card-header">
                    <!--- card-header Starts --->
                    <ol class="text-right">
                        <a href="index?view_languages" class="btn btn-danger">
                            <i class="text-white"></i> <span class="text-white">Back</span>
                        </a>
                    </ol>
                    <ol class="text-right">
                    </ol>
                    <i class="fa fa-money fa-fw"></i> Language Settings For <strong><?= $title; ?></strong>
                </div>
                <!--- card-header Ends --->
                <div class="card-body">
                    <!--- card-body Starts --->
                    <p class="lead">Enter Your Custom Language Variables For Site Here.</p>
                    <form method="post" enctype="multipart/form-data">
                        <!--- form Starts --->
                        <div class="form-group">
                            <textarea name="data" class="form-control" rows="50"
                                required=""><?= file_get_contents($file); ?></textarea>
                        </div>
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <input type="submit" name="update_language" class="form-control btn btn-success"
                                    value="Update Language Settings">
                            </div>
                        </div>
                        <!--- form-group row Ends --->
                    </form>
                    <!--- form Ends --->
                </div>
                <!--- card-body Ends --->
            </div>
            <!--- card Ends --->
        </div>
        <!--- col-lg-12 Ends --->
    </div>
    <!--- 2 row Ends --->
</div>
<script>
const textarea = document.querySelector('.form-control');

// Zeilennummerierung hinzufügen
for (let i = 1; i <= textarea.rows; i++) {
  const lineNumber = document.createElement('span');
  lineNumber.textContent = i;
  lineNumber.classList.add('line-number');

  textarea.parentNode.insertBefore(lineNumber, textarea);
}
</script>
<!--- container Ends --->
<?php
if(isset($_POST['update_language'])){		
$menu_lang = $_POST['data'];
$handle = fopen($file, "w");
if($handle){
fwrite($handle, $menu_lang);
fclose($handle);
$insert_log = $db->insert_log($admin_id,"language_settings",$id,"updated");
echo "<script>alert('Language Settings Has Been Updated.');</script>";
echo "<script>window.open('index?view_languages','_self');</script>";
}
}
?>
<?php } ?>