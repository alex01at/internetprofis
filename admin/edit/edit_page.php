<?php
// Sicherer Session-Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login','_self');</script>";
    exit();
}

/**
 * Optimierte URL-Bereinigung ohne veraltete Funktionen
 */
function sanitizeUrl($string, $space="-") {
    if(preg_match('/[اأإء-ي]/ui', $string)){
        return urlencode($string);
    }

    $char_map = [
        "Ğ" => "G", "Ü" => "U", "Ş" => "S", "İ" => "I", "Ö" => "O", "Ç" => "C",
        "ğ" => "g", "ü" => "u", "ş" => "s", "ı" => "i", "ö" => "o", "ç" => "c",
        "ä" => "ae", "ö" => "oe", "ü" => "ue", "Ä" => "Ae", "Ö" => "Oe", "Ü" => "Ue", "ß" => "ss"
    ];
    
    $string = strtr($string, $char_map);
    $string = strtolower($string);
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    $string = preg_replace("/[\s-]+/", " ", $string);
    $string = trim($string);
    $string = str_replace(" ", $space, $string);
    
    return $string;
}

if(isset($_GET['edit_page'])){
    $page_id = $input->get('edit_page');
    $edit_page = $db->select("pages", array('id' => $page_id));
    
    if($edit_page->rowCount() == 0){
        echo "<script>window.open('index?dashboard','_self');</script>";
        exit();
    }
    
    $row_edit = $edit_page->fetch();
    $page_url = $row_edit->url;
    
    $get_meta = $db->select("pages_meta", array("page_id" => $page_id, "language_id" => $adminLanguage));
    $row_meta = $get_meta->fetch();
    
    $page_title = $row_meta->title ?? '';
    $page_content = $row_meta->content ?? '';
}
?>

<!-- Summernote Assets -->
<link href="../../styles/summernote-0.8.18/summernote-bs4.min.css" rel="stylesheet">
<script type="text/javascript" src="../js/popper.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/summernote.js"></script>

<div class="main-container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="m-0 font-weight-bold"><i class="fa fa-edit text-primary mr-2"></i> Edit Page</h4>
                    <a href="index?pages" class="btn btn-danger btn-sm">Cancel</a>
                </div>
                
                <div class="card-body p-4">
                    <form action="" method="post">
                        <div class="form-group row">
                            <label class="col-md-3 font-weight-bold"> Page Title : </label>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($page_title); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3 font-weight-bold">
                                <label>Page Content:</label>
                                <br>
                                <small class="text-muted">Turn off HTML mode before saving.</small>
                            </div>
                            <div class="col-md-8">
                                <textarea id="summernote" class="form-control" name="content" rows="13" required><?= $page_content; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 font-weight-bold"> Page Url : </label>
                            <div class="col-md-8">
                                <input type="text" name="url" class="form-control" value="<?= htmlspecialchars($page_url); ?>" required>
                                <small class="text-info">The URL slug used in the address bar.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-8">
                                <input type="submit" name="update" class="btn btn-success btn-block" value="Update Page Settings">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Start typing here...',
            height: 350,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video', 'table']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>

<?php
if(isset($_POST['update'])){
    require_once("includes/removeJava.php");
    
    $title = $input->post('title');
    $content = removeJava($_POST['content']);
    $url = sanitizeUrl($input->post('url'));
    $date = date("F d, Y");
    
    // 1. Haupt-Tabelle aktualisieren
    $data = array("url" => $url, "date" => $date);
    $update = $db->update("pages", $data, array("id" => $page_id));
    
    if($update){
        // 2. Meta-Tabelle für die aktuelle Sprache aktualisieren
        $meta_data = array("title" => $title, "content" => $content);
        $update_meta = $db->update("pages_meta", $meta_data, array("page_id" => $page_id, 'language_id' => $adminLanguage));
        
        // Log-Eintrag
        $db->insert_log($admin_id, "page", $page_id, "updated");
        
        echo "<script>
            alert('Page has been updated successfully.');
            window.open('index?pages','_self');
        </script>";
    }
}
?>