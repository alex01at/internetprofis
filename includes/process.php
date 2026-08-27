<?php
$name = $_POST["name"];

// Regex zur Validierung
$regex = "/^(?!https?:\/\/)[a-zA-ZäöüßÄÖÜß ]+$/u";

// Validierung durchführen
if (!preg_match($regex, $name)) {
  // Fehlermeldung mit window.alert() anzeigen
  echo "<script>window.alert('Bitte geben Sie nur Buchstaben (einschließlich Umlaute), Groß- und Kleinschreibung und maximal ein Leerzeichen ein. URLs sind nicht erlaubt.');</script>";
  
  exit;
}
?>
