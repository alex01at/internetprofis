<?php

class Core{
	public $db;
	public $input;
	public $adminLanguage;
	public $admin_id;

	function __construct(){
		global $db;
		global $input;
		global $adminLanguage;
		global $admin_id;
		$this->db = $db;
		$this->input = $input;
		$this->adminLanguage = $adminLanguage;
		$this->admin_id = $admin_id;
	}

	public function checkPlugin(string $plugin, string $site = ""): int {
        // Gemeinsame Plugin-Abfrage nur einmal durchführen
        $pluginRowCount = $this->db->select("plugins", array("folder" => $plugin, "status" => 1))->rowCount();
        
        // Überprüfen, ob es sich um das Video-Plugin handelt und ob die Site "site" ist
        if ($plugin === "videoPlugin" && $site === "site" && $pluginRowCount != 0) {
            // Allgemeine Einstellungen abrufen
            $get_general_settings = $this->db->select("general_settings");
            if (!$get_general_settings) {
                throw new Exception("Fehler beim Abrufen der allgemeinen Einstellungen");
            }

            // Datenbankeintrag für API-Schlüssel holen
            $row_general_settings = $get_general_settings->fetch();
            $opentok_api_key = $row_general_settings->opentok_api_key;
            $opentok_api_secret = $row_general_settings->opentok_api_secret;

            // Überprüfen, ob API-Schlüssel vorhanden sind
            if (!empty($opentok_api_key) && !empty($opentok_api_secret)) {
                return 1; // Plugin aktiv und API-Schlüssel vorhanden
            } else {
                return 0; // API-Schlüssel fehlen
            }
        } else if ($plugin === "videoPlugin") {
            return 0; // Video-Plugin, aber nicht für die Site "site"
        } else {
            return $pluginRowCount; // Anzahl der aktiven Plugins zurückgeben
        }
    }
}