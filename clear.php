<?php
session_start();
session_destroy();
echo "Session gelöscht! Versuche jetzt die index.php neu zu laden.";