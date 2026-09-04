<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
?>
<div class="main-container">
   <!--- container Starts --->
   <div class="row">
      <!--- 2 row Starts --->
      <div class="col-lg-12">
         <!--- col-lg-12 Starts --->
         <?php 
$form_errors = Flash::render("form_errors");
$form_data = Flash::render("form_data");
if(is_array($form_errors)){
?>
         <div class="alert alert-danger">
            <!--- alert alert-danger Starts --->
            <ul class="list-unstyled mb-0">
               <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
               <li class="list-unstyled-item">
                  <?= $i ?>. <?= ucfirst($error); ?>
               </li>
               <?php } ?>
            </ul>
         </div>
         <!--- alert alert-danger Ends --->
         <?php } ?>
         <div class="card">
            <!--- card Starts --->
            <div class="card-header">
               <!--- card-header Starts --->
               <div class="float-right">
                  <div class="page-title">
                     <ol class="text-right">
                        <a href="index?theme_settings" class="btn btn-danger">
                           <i class="text-white"></i> <span class="text-white">Cancel</span>
                        </a>
                     </ol>
                  </div>
               </div>
               <h4 class="card-title">Insert Box</h4>
            </div>
            <!--- card-header Ends --->
            <div class="card-body">
               <!--- card-body Starts --->
               <form action="" method="post" enctype="multipart/form-data">
                  <!--- form Starts --->
                  <div class="form-group row">
                     <!--- form-group row Starts --->
                     <label class="col-md-3 control-label"> Section : </label>
                     <div class="col-md-6">
                        <select name="block_id" class="form-control" required="">
                        <?php
                        $preselect_block_id = $input->get('block_id');
                        $get_boxes_blocks = $db->query("SELECT * FROM home_layout_blocks WHERE language_id = :lang AND block_type = 'boxes' ORDER BY position ASC",array("lang" => $adminLanguage));
                        while($row_boxes_block = $get_boxes_blocks->fetch()){
                            $selected = ($preselect_block_id == $row_boxes_block->id) ? "selected" : "";
                        ?>
                        <option value="<?= $row_boxes_block->id; ?>" <?= $selected; ?>>Boxes (block #<?= $row_boxes_block->id; ?>)</option>
                        <?php } ?>
                        </select>
                     </div>
                  </div>
                  <!--- form-group row Ends --->
                  <div class="form-group row">
                     <!--- form-group row Starts --->
                     <label class="col-md-3 control-label"> Box Title : </label>
                     <div class="col-md-6">
                        <input type="text" name="box_title" class="form-control" required="">
                     </div>
                  </div>
                  <!--- form-group row Ends --->
                  <div class="form-group row">
                     <!--- form-group row Starts --->
                     <label class="col-md-3 control-label"> Box Description : </label>
                     <div class="col-md-6">
                        <textarea name="box_desc" class="form-control" cols="6" required=""></textarea>
                     </div>
                  </div>
                  <!--- form-group row Ends --->
                  <div class="form-group row">
                     <!--- form-group row Starts --->
                     <label class="col-md-3 control-label"> Box Image : </label>
                     <div class="col-md-6">
                        <input type="hidden" name="box_image" id="picker_box_image" value="">
                        <div class="mb-2"><img id="preview_box_image" src="" style="max-height:80px;" class="d-none"></div>
                        <button type="button" class="btn btn-outline-secondary" onclick="openImagePicker('box_image','section_boxes')">Choose Image</button>
                     </div>
                  </div>
                  <!--- form-group row Ends --->
                  <div class="form-group row">
                     <!--- form-group row Starts --->
                     <label class="col-md-3 control-label"></label>
                     <div class="col-md-6">
                        <input type="submit" name="submit" class="form-control btn btn-success" value="Insert Box">
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
<!--- container Ends --->
<?php
if(isset($_POST['submit'])){
   $rules = array(
   "block_id" => "required",
   "box_title" => "required",
   "box_desc" => "required",
   "box_image" => "required");
   $messages = array("box_desc" => "Box description Is Required.");
   $val = new Validator($_POST,$rules,$messages);
   if($val->run() == false){
     Flash::add("form_errors",$val->get_all_errors());
     Flash::add("form_data",$_POST);
     echo "<script> window.open('index?insert_box','_self');</script>";
   }else{
      $block_id = $input->post('block_id');
      $box_title = $input->post('box_title');
      $box_desc = $input->post('box_desc');
      $box_image = $input->post('box_image'); // already uploaded by the image picker
      $insert_box = $db->insert("section_boxes",array("language_id" => $adminLanguage,"block_id" => $block_id,"box_title" => $box_title,"box_desc" => $box_desc,"box_image" => $box_image,"isS3"=>$enable_s3));
      if($insert_box){
         $insert_id = $db->lastInsertId();
         $insert_log = $db->insert_log($admin_id,"section_box",$insert_id,"inserted");
         echo "<script>alert_success('One Box Successfully Inserted.','index?theme_settings');</script>";
      }
   }
}
?>
<?php } ?>