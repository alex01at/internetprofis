<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{

    $edit_id = $input->get('edit_article');
    $get_articles = $db->select("knowledge_bank",array("article_id" => $edit_id));
    if($get_articles->rowCount() == 0){
      echo "<script>window.open('index?dashboard','_self');</script>";
    }
    $row_articles = $get_articles->fetch();
    $article_id = $row_articles->article_id;
    $cat_id = $row_articles->cat_id;
    $article_url = $row_articles->article_url;
    $article_heading = $row_articles->article_heading;
    $article_body = $row_articles->article_body;
    $r_image = $row_articles->right_image;
    $t_image = $row_articles->top_image;
    $b_image = $row_articles->bottom_image;    
    $r_image_s3 = $row_articles->right_image_s3;
    $t_image_s3 = $row_articles->top_image_s3;
    $b_image_s3 = $row_articles->bottom_image_s3;
    $article_status = $row_articles->article_status;

    $show_right_image = getImageUrl2("knowledge_bank","right_image",$r_image);
    $show_top_image = getImageUrl2("knowledge_bank","top_image",$t_image);
    $show_bottom_image = getImageUrl2("knowledge_bank","bottom_image",$b_image);

    $get_categories = $db->select("article_cat",array("article_cat_id" => $cat_id));
    $row_categories = $get_categories->fetch();
    $article_cat_title = $row_categories->article_cat_title;

    if(isset($_GET['delete_image'])){
      $remove_image = $input->get("delete_image");
      $update_article = $db->update("knowledge_bank",array($remove_image => ''),array("article_id"=>$article_id));
      if($update_article){
        deleteFromS3("../article/article_images/{$row_articles->$remove_image}");
        echo "<script>window.open('index?edit_article=$article_id','_self');</script>";
      }
    }

?>
<link href="../../styles/summernote-0.8.16/summernote-bs4.min.css" rel="stylesheet">
<script type="text/javascript" src="../js/popper.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/summernote.js"></script>
<div class="main-container">
<h4 class="mb-4"><i class="fa fa-book"></i> Edit Article</h4>
<div class="row">
  <div class="col-md-12">
  <?php 
  $form_errors = Flash::render("form_errors");
  $form_data = Flash::render("form_data");
  if(is_array($form_errors)){
  ?>
  <div class="alert alert-danger"><!--- alert alert-danger Starts --->
  <ul class="list-unstyled mb-0">
  <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
  <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
  <?php } ?>
  </ul>
  </div><!--- alert alert-danger Ends --->
  <?php } ?>
    <div class="card">
      <div class="card-header">
        <h4 class="h4">Edit Article</h4>
      </div>
      <div class="card-body card-block">
        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="row form-group">
            <div class="col col-md-3">
              <label for="text-input" class=" form-control-label">Article Heading</label>
            </div>
            <div class="col-12 col-md-7">
              <input value="<?= $article_heading; ?>" type="text" id="text-input" name="article_heading" class="form-control">
            </div>
          </div>
          <div class="row form-group">
            <div class="col col-md-3">
              <label for="select" class=" form-control-label">Article Status</label>
            </div>
            <div class="col-12 col-md-7">
              <select name="article_status" id="select" class="form-control">
                  <option value="active">Active</option>
                  <option value="draft">Not Active</option>
              </select>
            </div>
          </div>
          <div class="form-group row"><!--- form-group row Starts --->
               <label class="col-md-3 control-label"> Select Article's Category : </label>
               <div class="col-md-7">
                   <select name="cat_id" class="form-control" required>
                      <option value="<?= $cat_id; ?>"> <?= $article_cat_title; ?> </option>
                       <?php
                        $get_cats = $db->query("select * from article_cat where not article_cat_id='$cat_id'");
                        while($row_cats = $get_cats->fetch()){
                          $article_cat_id = $row_cats->article_cat_id;
                          $article_cat_title = $row_cats->article_cat_title;
                          echo "<option value='$article_cat_id'>$article_cat_title</option>";
                        }
                      ?>
                    </select>
               </div>
           </div><!--- form-group row Ends --->
          <div class="row form-group">
            <div class="col col-md-3">
              <label for="textarea-input" class=" form-control-label">Article Body</label>
            </div>
            <div class="col-12 col-md-7">
              <textarea name="article_body" id="textarea-input" rows="20" placeholder="Start Typing Here..." class="form-control"><?= $article_body; ?></textarea>
            </div>
          </div>
          <div class="row form-group">
          <div class="col col-md-3">
          <label for="file-input" class=" form-control-label">Right Image (optional)</label>
          </div>
          <div class="col-12 col-md-9">
          <input type="hidden" name="right_image" id="picker_right_image" value="<?= htmlspecialchars($r_image); ?>">
          <div class="mb-2"><img id="preview_right_image" src="<?= !empty($r_image) ? $show_right_image : '../article/article_images/No-image.jpg'; ?>" width="70" height="55"></div>
          <button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('right_image','knowledge_bank')">Choose Image</button>
            <?php if(!empty($r_image)){ ?>
            <br>
            <a href="index?edit_article=<?= $article_id; ?>&delete_image=right_image" class="btn btn-sm btn-danger mt-2"><i class="fa fa-trash"></i> Remove Image</a>
            <?php } ?>
          </div>
          </div>
          <div class="row form-group">
            <div class="col col-md-3">
           <label class=" form-control-label">Top Image (optional)</label></div>
            <div class="col-12 col-md-9">
            <input type="hidden" name="top_image" id="picker_top_image" value="<?= htmlspecialchars($t_image); ?>">
            <div class="mb-2"><img id="preview_top_image" src="<?= !empty($t_image) ? $show_top_image : '../article/article_images/No-image.jpg'; ?>" width="70" height="55"></div>
            <button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('top_image','knowledge_bank')">Choose Image</button>
           <?php if(!empty($t_image)){ ?>
              <br>
              <a href="index?edit_article=<?= $article_id; ?>&delete_image=top_image" class="btn btn-sm btn-danger mt-2"><i class="fa fa-trash"></i> Remove Image</a>
            <?php } ?>
            </div>
          </div>
          <div class="row form-group">
            <div class="col col-md-3"><label for="file-multiple-input" class=" form-control-label">Bottom Image (optional)</label></div>
            <div class="col-12 col-md-9">
              <input type="hidden" name="bottom_image" id="picker_bottom_image" value="<?= htmlspecialchars($b_image); ?>">
              <div class="mb-2"><img id="preview_bottom_image" src="<?= !empty($b_image) ? $show_bottom_image : '../article/article_images/No-image.jpg'; ?>" width="70" height="55"></div>
              <button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('bottom_image','knowledge_bank')">Choose Image</button>
            <?php if(!empty($b_image)){ ?>
              <br>
              <a href="index?edit_article=<?= $article_id; ?>&delete_image=bottom_image" class="btn btn-sm btn-danger mt-2"><i class="fa fa-trash"></i> Remove Image</a>
            <?php } ?>
            </div>
          </div>
          <div class="row form-group">
            <div class="col col-md-3"></div>
            <div class="col-12 col-md-9">
            <button type="submit" name="submit" class="btn btn-success">Update Article</button>
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
    height: 200
  });
</script>
<?php

require_once("includes/removeJava.php");

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
    echo "<script> window.open(window.location.href,'_self');</script>";
  }else{

    $article_heading = $input->post('article_heading');
    $cat_id = $input->post('cat_id');
    $article_status = $input->post('article_status');
    $article_body = removeJava($_POST['article_body']);
    $right_image = $input->post('right_image'); // already uploaded by the image picker, or unchanged
    $top_image = $input->post('top_image');
    $bottom_image = $input->post('bottom_image');
    $right_image_s3 = ($right_image == $r_image) ? $r_image_s3 : $enable_s3;
    $top_image_s3 = ($top_image == $t_image) ? $t_image_s3 : $enable_s3;
    $bottom_image_s3 = ($bottom_image == $b_image) ? $b_image_s3 : $enable_s3;

    $update_article = $db->update("knowledge_bank",array("cat_id"=>$cat_id,"article_heading"=>$article_heading,"article_body"=>$article_body,"right_image"=>$right_image,"top_image"=>$top_image,"bottom_image"=>$bottom_image,"right_image_s3"=>$right_image_s3,"top_image_s3"=>$top_image_s3,"bottom_image_s3"=>$bottom_image_s3,"article_status"=>$article_status),array("article_id"=>$edit_id));

    if($update_article){
      $insert_log = $db->insert_log($admin_id,"article",$edit_id,"updated");
      echo "<script>alert('Article Updated successfully.');</script>";
      echo "<script>window.open('index?view_articles','_self');</script>";
    }

  }

}
?>
<?php } ?>
