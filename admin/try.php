<?php
@session_start();
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login', '_self');</script>";
} else {
// Include the translation file
include 'deutsch.php';

// Initialize an empty array to store all translations
$translations = array();
$searchTerm = ''; // Initialisieren Sie $searchTerm

// Check if a search term was submitted
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
    $translation = isset($translations[$searchTerm]) ? $translations[$searchTerm] : 'Übersetzung nicht gefunden';
}
?>
<div class="main-container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Übersetzungsformular</div>
                    <div class="card-body">
                        <form method="GET">
                            <div class="form-group">
                                <input type="text" class="form-control" name="searchTerm" placeholder="Suche nach Übersetzung">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Suchen</button>
                            </div>
                        </form>
                        <!-- Display the translation result -->
                        <div id="translation-result">
                            <?php if (isset($translations)): ?>
                                <p class="mt-3">Übersetzung: <?php echo $translations[$searchTerm] ?? 'Übersetzung nicht gefunden'; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
    $translation = isset($translations[$searchTerm]) ? $translations[$searchTerm] : 'Übersetzung nicht gefunden';
}
?>

<!-- Display the translation result -->
<div id="translation-result">
    <?php if (isset($translation)): ?>
        <p>Übersetzung: <?php echo $translation; ?></p>
    <?php endif; ?>


<?php } ?>