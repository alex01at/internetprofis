<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
?>
<link href="../../styles/summernote-0.8.16/summernote-bs4.min.css" rel="stylesheet">
<script type="text/javascript" src="../js/popper.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/summernote.js"></script>
<div class="main-container">
  <div class="row">
    <div class="col-md-12">
      <?php 
        $form_errors = Flash::render("form_errors");
        $form_data = Flash::render("form_data");
        if(is_array($form_errors)){
        ?>
      <div class="alert alert-danger">
        <!--- alert alert-danger Starts --->
        <ul class="list-unstyled mb-0">
          <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
          <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
          <?php } ?>
        </ul>
      </div>
      <!--- alert alert-danger Ends --->
      <?php } ?>
      <div class="card">
        <div class="card-header">
          <ol class="text-right">
            <a href="index?view_articles" class="btn btn-danger">
              <i class="text-white"></i> <span class="text-white">Cancel</span>
            </a>
          </ol>
          <h4 class="h4">Insert New Article</h4>
        </div>
        <div class="card-body card-block">
          <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="row form-group">
              <div class="col col-md-3"><label for="text-input" class=" form-control-label">Article Heading</label>
              </div>
              <div class="col-12 col-md-9"><input type="text" id="text-input" name="article_heading"
                  class="form-control" required=""><small class="form-text text-muted"></small></div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3"><label for="select" class=" form-control-label">Article Status</label></div>
              <div class="col-12 col-md-9">
                <select name="article_status" id="select" class="form-control" required="">
                  <option value="">Please select</option>
                  <option value="active">Active</option>
                  <option value="draft">Not Active</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Select Article's Category : </label>
              <div class="col-md-6">
                <select name="cat_id" class="form-control" required>
                  <option value=""> Select Article Category </option>
                  <?php
                      $get_cats = $db->select("article_cat",array("language_id" => $adminLanguage));
                      while($row_cats = $get_cats->fetch()){
                      $article_cat_id = $row_cats->article_cat_id;
                      $article_cat_title = $row_cats->article_cat_title;
                      echo "<option value='$article_cat_id'>$article_cat_title</option>";
                      }
                      ?>
                </select>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="row form-group">
              <div class="col col-md-3"><label for="textarea-input" class=" form-control-label">Article Body</label>
              </div>
              <div class="col-12 col-md-9"><textarea name="article_body" id="textarea-input" rows="9"
                  placeholder="Start Typing Here..." class="form-control"></textarea></div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3"><label class=" form-control-label">Right Image
                  (optional)</label></div>
              <div class="col-12 col-md-9">
                <input type="hidden" name="right_image" id="picker_right_image" value="">
                <div class="mb-2"><img id="preview_right_image" src="" style="max-height:80px;" class="d-none"></div>
                <button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('right_image','knowledge_bank')">Choose Image</button>
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3"><label class=" form-control-label">Top Image (optional)</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="hidden" name="top_image" id="picker_top_image" value="">
                <div class="mb-2"><img id="preview_top_image" src="" style="max-height:80px;" class="d-none"></div>
                <button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('top_image','knowledge_bank')">Choose Image</button>
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3"><label class=" form-control-label">Bottom Image
                  (optional)</label></div>
              <div class="col-12 col-md-9">
                <input type="hidden" name="bottom_image" id="picker_bottom_image" value="">
                <div class="mb-2"><img id="preview_bottom_image" src="" style="max-height:80px;" class="d-none"></div>
                <button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('bottom_image','knowledge_bank')">Choose Image</button>
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3"></div>
              <div class="col-12 col-md-9">
                <button type="submit" name="submit" class="btn btn-success">Insert Article</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('textarea').summernote({
    placeholder: 'Start Typing Here...',
    height: 150
  });
</script>
<?php
require_once("includes/removeJava.php");
include("includes/sanitize_url.php");
if(isset($_POST['submit'])){
  $rules = array(
  "article_heading" => "required",
  "article_status" => "required",
  "cat_id" => "required",
  "article_body" => "required");
  $messages = array("cat_id" => "You must need to select a category for Article.");
  $val = new Validator($_POST,$rules,$messages);
  if($val->run() == false){
    Flash::add("form_errors",$val->get_all_errors());
    Flash::add("form_data",$_POST);
    echo "<script> window.open('index?insert_article','_self');</script>";
  }else{
    $article_heading = $input->post('article_heading');
    $article_url = slug($article_heading);
    $cat_id = $input->post('cat_id');
    $article_status = $input->post('article_status');
    $article_body = removeJava($_POST['article_body']);
    $right_image = $input->post('right_image'); // already uploaded by the image picker
    $top_image = $input->post('top_image');
    $bottom_image = $input->post('bottom_image');
    $insert_article = $db->insert("knowledge_bank",array("language_id" => $adminLanguage,"cat_id"=>$cat_id,"article_url"=>$article_url,"article_heading"=>$article_heading,"article_body"=>$article_body,"right_image"=>$right_image,"top_image"=>$top_image,"bottom_image"=>$bottom_image,"right_image_s3"=>$enable_s3,"top_image_s3"=>$enable_s3,"bottom_image_s3"=>$enable_s3,"article_status"=>$article_status));
    if($insert_article){
      $insert_id = $db->lastInsertId();
      $insert_log = $db->insert_log($admin_id,"article",$insert_id,"inserted");
      echo "<script>alert('Article inserted successfully.');</script>";
      echo "<script>window.open('index?view_articles','_self');</script>";
    }
  }
}
?>
<?php } ?>