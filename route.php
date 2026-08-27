<?php
session_start();


// Die angeforderte URL analysieren
$request_uri = $_SERVER['REQUEST_URI'];

// Hier kannst du Logik hinzufügen, um die angeforderte Ressource zu bestimmen
// und entsprechend darauf zu reagieren. Zum Beispiel könntest du eine
// Liste von Routen definieren und basierend darauf entscheiden, welche Datei
// oder welcher Controller die Anfrage behandeln soll.

// Beispiel: Wenn die angeforderte URL "/about" ist, zeige die "about.php" Datei an
if ($request_uri == '/login') {
    include('sites/login.php');
} else {
    // Wenn die Ressource nicht gefunden wurde, eine Fehlerseite ausgeben
    http_response_code(404);
    echo 'Seite nicht gefunden';
}
?>