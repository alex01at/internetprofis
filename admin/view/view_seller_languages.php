<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login','_self');</script>";
    exit(); // Wichtig, um die weitere Ausführung zu stoppen
}
?>
<div class="main-container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li class="active"> 
                                    <a href="index?insert_seller_language" class="btn btn-success"> 
                                        <i class="fa fa-plus-circle"></i> Add Language
                                    </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <h4 class="h4">View All Languages</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover"> <!-- table-hover für bessere Optik -->
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Language Title</th>
                                    <th>Delete</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 0;
                                    // Falls deine select-Funktion kein ORDER BY im String mag:
                                    $get_seller_languages = $db->select("seller_languages"); 
                                    
                                    while($row_seller_languages = $get_seller_languages->fetch()){
                                        $language_id = $row_seller_languages->language_id;
                                        $language_title = $row_seller_languages->language_title;
                                        $i++;
                                ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= htmlspecialchars($language_title); ?></td>
                                    <td>
                                        <a href="index?delete_seller_language=<?= $language_id; ?>"
                                           onclick="return confirm('Möchten Sie diese Sprache wirklich löschen?');"
                                           class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </td>
                                    <td>
                                        <a href="index?edit_seller_language=<?= $language_id; ?>"
                                           class="btn btn-success btn-sm">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>