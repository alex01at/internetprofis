<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
    header("Location: login"); // Sauberer PHP-Redirect
    exit();
} else {
?>

<div class="main-container">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h4 class="m-0 font-weight-bold text-dark">
                <i class="fa fa-file-text text-primary mr-2"></i> Pages Management
            </h4>
            <a href="index?insert_page" class="btn btn-success">
                <i class="fa fa-plus-circle"></i> Add Page
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Title</th>
                            <th>Language</th>
                            <th>Date Updated</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // SQL-Power-Move: Alles in einer einzigen Abfrage holen!
                    $query = "SELECT p.*, pm.title as page_title, l.title as language_title 
                              FROM pages p 
                              JOIN pages_meta pm ON p.id = pm.page_id 
                              JOIN languages l ON pm.language_id = l.id 
                              WHERE pm.language_id = :adminLang";
                    
                    $selPages = $db->query($query, array("adminLang" => $adminLanguage));

                    if ($selPages->rowCount() > 0) {
                        while ($page = $selPages->fetch()) {
                            $p_id = $page->id;
                            // Wir erstellen eine einzigartige ID für jedes Modal
                            $modal_id = "pageActionsModal" . $p_id;
                    ?>
                        <tr>
                            <td class="align-middle font-weight-bold"><?= htmlspecialchars($page->page_title); ?></td>
                            <td class="align-middle">
                                <span class="badge badge-info p-2"><?= htmlspecialchars($page->language_title); ?></span>
                            </td>
                            <td class="align-middle text-muted"><?= date("d.m.Y", strtotime($page->date)); ?></td>
                            <td class="text-center align-middle">
                                
                                <!-- Button mit einzigartigem Target -->
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#<?= $modal_id; ?>">
                                    <i class="fa fa-cog"></i> Actions
                                </button>

                                <!-- Das Modal (einzigartig pro Zeile) -->
                                <div class="modal fade" id="<?= $modal_id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Actions for: <?= htmlspecialchars($page->page_title); ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-4 text-center">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <a class="btn btn-outline-success btn-block" href="../pages/<?= $page->url; ?>" target="_blank">
                                                            <i class="fa fa-eye d-block mb-1"></i> Preview
                                                        </a>
                                                    </div>
                                                    <div class="col-6">
                                                        <a class="btn btn-outline-primary btn-block" href="index?edit_page=<?= $p_id; ?>">
                                                            <i class="fa fa-edit d-block mb-1"></i> Edit
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <a class="btn btn-danger mr-auto confirm-delete" href="index?delete_page=<?= $p_id; ?>">
                                                    <i class="fa fa-trash"></i> Delete Page
                                                </a>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Ende -->

                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-4'>No pages found. <a href='index?insert_page'>Create one now?</a></td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Kleiner Bonus: Sicherheitsabfrage vor dem Löschen
document.querySelectorAll('.confirm-delete').forEach(btn => {
    btn.onclick = function() {
        return confirm("Are you sure? This action cannot be undone.");
    };
});
</script>

<?php } ?>