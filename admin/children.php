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
                                <li class="active"><a href="index?insert_child_cat" class="btn btn-success">
                                        <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Sub
                                            Category</span>
                                    </a></li>
                            </ol>
                        </div>
                    </div>
                    <h4 class="h4">All Sub Categories</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sub Category Id</th>
                                    <th>Sub Category Title</th>
                                    <th>Sub Category Description</th>
                                    <th>Parent Category Title</th>
                                    <th>Delete Sub Category</th>
                                    <th>Edit Sub Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $per_page = 10;
                                if(isset($_GET['view_child_cats'])){
                                    $page = $input->get('view_child_cats');
                                    if($page == 0){ $page = 1; }
                                }else{
                                    $page = 1;
                                }
                                $i = ($page * $per_page) - 10;
                                $start_from = ($page - 1) * $per_page;
                                $get_child_cats = $db->query("select * from categories_children order by 1 DESC LIMIT :limit OFFSET :offset","",array("limit"=>$per_page,"offset"=>$start_from));
                                while($row_child_cats = $get_child_cats->fetch()){
                                    $child_id = $row_child_cats->child_id;
                                    $child_parent_id = $row_child_cats->child_parent_id;
                                    $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $adminLanguage));
                                    $row_meta = $get_meta->fetch();
                                    $child_title = $row_meta->child_title;
                                    $child_desc = $row_meta->child_desc;
                                    $get_meta_parent = $db->select("cats_meta",array("cat_id" => $child_parent_id, "language_id" => $adminLanguage));
                                    $row_meta_parent = $get_meta_parent->fetch();
                                    $cat_title = $row_meta_parent->cat_title;
                                    $i++;
                                    $warning_message1 = '';
                                    $warning_message2 = '';
                                    if ($adminLanguage != 1) {
                                        $get_default_child_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => 1));
                                        $default_child_language_meta = $get_default_child_meta->fetch();
                                        $default_child_title = $default_child_language_meta->child_title;
                                        $default_child_desc = $default_child_language_meta->child_desc;
                                        $get_meta_parent = $db->select("cats_meta",array("cat_id" => $child_parent_id, "language_id" => 1));
                                        $row_meta_parent = $get_meta_parent->fetch();
                                        $default_cat_title = $row_meta_parent->cat_title;
                                        if(empty($child_title)){
                                            $warning_message1 = '<span class="text-danger">(Translation Needed: ' . $default_child_title . ')</span>';
                                        }
                                        if(empty($child_desc)){
                                            $warning_message2 = '<span class="text-danger">(Translation Needed: ' . $default_child_desc . ')</span>';
                                        }
                                        if(empty($cat_title)){
                                            $warning_message3 = '<span class="text-danger">(Mainlanguage Title: ' . $default_cat_title . ')</span>';
                                        }
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <?= $i; ?>
                                    </td>
                                    <td>
                                        <?= ($adminLanguage != 1 && !empty($warning_message1)) ? $warning_message1 : $child_title; ?>
                                    </td>
                                    <td width="400">
                                        <?= ($adminLanguage != 1 && !empty($warning_message2)) ? $warning_message2 : $child_desc; ?>
                                    </td>
                                    <td>
                                    <?= ($adminLanguage != 1 && !empty($warning_message2)) ? $warning_message3 : $cat_title; ?>
                                    </td>
                                    <td>
                                        <a href="index?delete_child_cat=<?= $child_id; ?>"
                                            onclick="return confirm('Do you really want to delete this sub category permanently.');"
                                            class="btn btn-danger">
                                            <i class="fa fa-trash text-white"></i> <span class="text-white">Delete</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="index?edit_child_cat=<?= $child_id; ?>" class="btn btn-success">
                                            <i class="fa fa-pencil text-white"></i> <span class="text-white">Edit
                                                Cat</span>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        <ul class="pagination">
                            <?php
                            $query = $db->query("select * from categories_children order by 1 DESC");
                            $total_records = $query->rowCount();
                            $total_pages = ceil($total_records / $per_page);
                            echo "<li class='page-item'><a href='index?view_child_cats=1' class='page-link'>First Page</a></li>";
                            echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?view_child_cats=1'>1</a></li>";
                            $i = max(2, $page - 5);
                            if ($i > 2)
                                echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
                            for (; $i < min($page + 6, $total_pages); $i++) {
                                echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?view_child_cats=".$i."' class='page-link'>".$i."</a></li>";
                            }
                            if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}
                            if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?view_child_cats=$total_pages'>$total_pages</a></li>";}
                            echo "<li class='page-item'><a href='index?view_child_cats=$total_pages' class='page-link'>Last Page </a></li>";
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>