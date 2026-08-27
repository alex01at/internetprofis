<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_email'])){
    header("Location: login");
    exit();
}
if(!isset($_SESSION['admin_email'])){
    header("Location: login");
    exit();
}

/**
 * Ersetzt die veraltete utf8_encode Funktion und bereinigt die URL
 */
function sanitizeUrl($string, $space="-") {
    // Arabische Zeichen beibehalten
    if(preg_match('/[اأإء-ي]/ui', $string)){
        return urlencode($string);
    }

    // Türkische/Deutsche Sonderzeichen Mapping
    $char_map = [
        "Ğ" => "G", "Ü" => "U", "Ş" => "S", "İ" => "I", "Ö" => "O", "Ç" => "C",
        "ğ" => "g", "ü" => "u", "ş" => "s", "ı" => "i", "ö" => "o", "ç" => "c",
        "ä" => "ae", "ö" => "oe", "ü" => "ue", "Ä" => "Ae", "Ö" => "Oe", "Ü" => "Ue", "ß" => "ss"
    ];
    
    $string = strtr($string, $char_map);
    $string = strtolower($string);
    
    // Nur Alphanumerische Zeichen und Leerzeichen erlauben
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    // Mehrfache Leerzeichen/Bindestriche säubern
    $string = preg_replace("/[\s-]+/", " ", $string);
    $string = trim($string);
    $string = str_replace(" ", $space, $string);
    
    return $string;
}

?>
<link href="../../styles/summernote-0.8.18/summernote-bs4.min.css" rel="stylesheet">
<script type="text/javascript" src="../js/popper.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/summernote.js"></script>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="fa fa-file-text-o"></i> Pages</h1>
            </div>
        </div>
    </div>
</div>

<div class="main-container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="m-0 font-weight-bold">Insert New Page</h4>
                    <a href="index?pages" class="btn btn-danger btn-sm">Cancel</a>
                </div>
                <div class="card-body p-4">
                    <form action="" method="post">
                        <div class="form-group row">
                            <label class="col-md-3 font-weight-bold"> Page Title : </label>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" placeholder="Enter page title" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 font-weight-bold">Page Content:</label>
                            <div class="col-md-8">
                                <textarea id="summernote" class="form-control" name="content" rows="13" required></textarea>
                                <small class="text-muted">You can use HTML mode for advanced layouts.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 font-weight-bold"> Page URL / Slug : </label>
                            <div class="col-md-8">
                                <input type="text" name="url" class="form-control" placeholder="e.g. terms-and-conditions" required>
                                <small class="text-info">Will be automatically formatted.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-8">
                                <input type="submit" name="insert" class="btn btn-success btn-block" value="Create Page Now">
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
            placeholder: 'Start typing your page content...',
            height: 350,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    // Hier könnte man einen Image-Uploader einbauen
                }
            }
        });
    });
</script>

<?php
if(isset($_POST['insert'])){
    require_once("includes/removeJava.php");
    
    $title = $input->post('title');
    $content = removeJava($_POST['content']);
    $url = sanitizeUrl($input->post('url'));
    $date = date("F d, Y");
    
    // 1. In die Haupt-Tabelle einfugen
    $data = array("url" => $url, "date" => $date);
    $insert = $db->insert("pages", $data); 
    
    if($insert){
        $insert_id = $db->lastInsertId();
        
        // 2. Meta-Daten für alle installierten Sprachen anlegen
        $get_languages = $db->select("languages");
        while($row_languages = $get_languages->fetch()){
            $lang_id = $row_languages->id;
            // Standardmäßig leer einfügen
            $db->insert("pages_meta", array(
                "page_id" => $insert_id, 
                "language_id" => $lang_id,
                "title" => '',
                "content" => ''
            ));
        }
        
        // 3. Den Inhalt für die aktuell gewählte Admin-Sprache aktualisieren
        $db->update("pages_meta", 
            array("title" => $title, "content" => $content), 
            array("page_id" => $insert_id, "language_id" => $adminLanguage)
        );
        
        // Log schreiben
        $db->insert_log($admin_id, "page", $insert_id, "inserted");
        
        echo "<script>
            alert('Page has been created successfully!');
            window.open('index?pages','_self');
        </script>";
    }
}
?>