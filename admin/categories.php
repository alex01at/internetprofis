<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
?>
<div class="main-container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li class="active"><a href="index?insert_cat" class="btn btn-success">
                                        <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Category</span>
                                    </a></li>
                            </ol>
                        </div>
                    </div>
                    <h4 class="h4">
                        <i class="fa fa-money-bill-alt"></i> View All Categories
                    </h4>
                </div>
                <!--- card-header Ends --->
                <div class="card-body">
                    <!--- card-body Starts --->
                    <table class="table table-bordered">
                        <!--- table table-bordered table-hover Starts --->
                        <thead>
                            <tr>
                                <th>Category Id</th>
                                <th>Category Title</th>
                                <th>Category Description</th>
                                <th>Category Featured</th>
                                <th>Image</th>
                                <th>Delete Category</th>
                                <th>Edit Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--- tbody Starts --->
                            <?php
                            $i = 0;
                            $get_cats = $db->select("categories");
                            while ($row_cats = $get_cats->fetch()) {
                                $cat_id = $row_cats->cat_id;
                                $cat_featured = $row_cats->cat_featured;
                                $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $adminLanguage));
                                $row_meta = $get_meta->fetch();
                                $cat_title = $row_meta->cat_title;
                                $cat_desc = $row_meta->cat_desc;
                                $show_image = getImageUrl("categories",$row_cats->cat_image);
                                $i++;

                                // Check if language ID is not 1 and display warning with content from language ID 1
                                $warning_message1 = '';
                                $warning_message2 = '';

                                if ($adminLanguage != 1) {
                                    // Get content from language ID 1
                                    $get_default_language_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => 1));
                                    $default_language_meta = $get_default_language_meta->fetch();
                                    $default_cat_title = $default_language_meta->cat_title;
                                    $default_cat_desc = $default_language_meta->cat_desc;

                                    // Check if the current content is empty, show the translation needed warning
                                    if(empty($cat_title)){
                                        $warning_message1 = '<span class="text-danger">(Translation Needed: ' . $default_cat_title . ')</span>';
                                    }

                                    if(empty($cat_desc)){
                                        $warning_message2 = '<span class="text-danger">(Translation Needed: '  . $default_cat_desc . ')</span>';
                                    }
                                }
                            ?>
                                <tr>
                                    <td width="20px">
                                        <?= $i; ?>
                                    </td>
                                    <td>
                                        <?= ($adminLanguage != 1 && !empty($warning_message1)) ? $warning_message1 : $cat_title; ?>
                                    </td>
                                    <td width="400">
                                        <?= ($adminLanguage != 1 && !empty($warning_message2)) ? $warning_message2 : $cat_desc; ?>
                                    </td>
                                    <td width="20" class="text-center">
                                        <?= $cat_featured; ?>
                                    </td>
                                    <td><?php if(!empty($show_image)) {?> <img src="<?=$show_image?>" <?php } ?></td>
                                    <td width="auto">
                                        <a href="index?delete_cat=<?= $cat_id; ?>"
                                           onclick="return confirm('Deleting this category will delete all its sub-categories. Do you wish to proceed?');"
                                           class="btn btn-danger">
                                            <i class="fa fa-trash text-white"></i> <span class="text-white">Delete</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="index?edit_cat=<?= $cat_id; ?>" class="btn btn-success">
                                            <i class="fa fa-pencil text-white"></i> <span class="text-white">Edit Cat</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <!--- tbody Ends --->
                    </table>
                    <!--- table table-bordered table-hover Ends --->
                </div>
                <!--- card-body Ends --->
            </div>
            <!--- card Ends --->
        </div>
        <!--- col-lg-12 Ends --->
    </div>
    <!--- 2 row Ends --->
</div>


<?php } ?>