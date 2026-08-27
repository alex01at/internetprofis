<div class="col-sm-7">

</div>

<div class="col-sm-5">

   <div class="user-area dropdown float-right">

     <button class="btn btn-outline-secondary btn-sm dropdown-toggle text=white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

     <?php if(!empty($admin_image)){ ?>
         <img src="<?= getImageUrl("admins",$admin_image); ?>" width="30" height="30" class="rounded-circle text-white">
     <?php }else{ ?>
         <img src="admin_images/empty-image.png" width="30" height="30" class="rounded-circle text-white">
     <?php } ?>

     &nbsp; <?= $admin_name; ?>  &nbsp; <span class="caret"></span>

    </button>

     <div class="user-menu dropdown-menu">

         <a class="nav-link" href="index?dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>

         <a class="nav-link"  href="index?user_profile=<?= $admin_id; ?>">
             <i class="fa fa-user"></i> My Profile
         </a>

         <div class="dropdown-divider"></div>
         <a class="nav-link" href="logout"><i class="fa fa-power-off"></i> Logout</a>

     </div>
   </div>

</div>
<?php
function langTranslation_FormFields()
{
    global $con,$config;

    $id = validate_input($_POST['id']);
    $type = validate_input($_POST['cat_type']);
    $field_tpl = '<input type="hidden" id="category_id" value="'.$id.'"><input type="hidden" id="category_type" value="'.$type.'">';
    if ($id) {
        $sql = "SELECT id,code,name FROM `".$config['db']['pre']."languages` where active = '1' and code != 'en'";
        $query = mysqli_query($con,$sql);
        $rows = mysqli_num_rows($query);
        if($rows > 0){
            while($fetch = mysqli_fetch_array($query)){

                $sql2 = "SELECT * FROM `".$config['db']['pre']."category_translation` where lang_code = '".$fetch['code']."' and 	translation_id = '$id' and category_type = '$type' LIMIT 1";
                $query2 = mysqli_query($con,$sql2);
                $info = mysqli_fetch_assoc($query2);
                $title = isset($info['title'])? $info['title']: '';
                $slug = isset($info['slug'])? $info['slug']: '';
                if($type == "custom_option"){
                    $field_tpl .= '
                    <div class="row translate_row">
                    <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                    <label class="col-md-3 control-label">' . $fetch['name'] . '</label>
                    <div class="col-md-9">
                    <input type="text" value="' . $title . '" class="form-control cat_title" placeholder="In ' . $fetch['name'] . '">
                    <input type="hidden" class="lang_code" value="' . $fetch['code'] . '">
                    </div>
                    </div>
                    </div>
                    </div>
                    ';
                }else{
                    $field_tpl .= '
                    <div class="row translate_row">
                    <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                    <label class="col-md-3 control-label">' . $fetch['name'] . '</label>
                    <div class="col-md-9">
                    <input type="text" value="' . $title . '" class="form-control cat_title" placeholder="In ' . $fetch['name'] . '">
                    </div>
                    </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                    <label class="col-md-3 control-label">Slug</label>
                    <div class="col-md-9">
                    <input type="text" value="' . $slug . '" class="form-control cat_slug" placeholder="Slug">
                    </div>
                    </div>
                    </div>
                    <input type="hidden" class="lang_code" value="' . $fetch['code'] . '">
                    </div>
                    ';
                }

            }
        }else{
            $field_tpl .= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            No language activated. Your site run with single language. </div>';
        }
        echo $field_tpl;
        die();
    } else {
        echo 0;
        die();
    }
}
?>