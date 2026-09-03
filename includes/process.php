<?php
$name = $_POST["name"];

// Regex zur Validierung
$regex = "/^(?!https?:\/\/)[a-zA-ZäöüßÄÖÜß ]+$/u";

// Validierung durchführen
if (!preg_match($regex, $name)) {
  // Fehlermeldung mit window.alert() anzeigen
  echo "<script>window.alert(".json_encode($lang['validation']['letters_only_no_urls']).");</script>";
  
  exit;
}
?>
