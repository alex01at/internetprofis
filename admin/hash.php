<?php
// Ersetze 'mein_neues_passwort' durch dein gewünschtes Passwort
$neues_passwort = 'gigtodo'; 

$hash = password_hash($neues_passwort, PASSWORD_DEFAULT);

echo "Dein neuer Hash ist: <br><b>" . $hash . "</b>";
?>