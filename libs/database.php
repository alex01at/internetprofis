<?php
class Database {

    public $con;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        // Wir setzen die Standard-Werte aus der config.php
        $host = defined('DB_HOST') ? DB_HOST : 'localhost';
        $user = defined('DB_USER') ? DB_USER : '';
        $pass = defined('DB_PASS') ? DB_PASS : '';
        $db_name = defined('DB_NAME') ? DB_NAME : '';

        // Falls Session-Daten existieren (z.B. durch Installer/Umzug), überschreiben diese die Konstanten
        if (isset($_SESSION["db_host"]) && !empty($_SESSION["db_host"])) {
            $host = $_SESSION["db_host"];
            $user = $_SESSION["db_username"];
            $pass = $_SESSION["db_pass"];
            $db_name = $_SESSION["db_name"];
        }

        // Fix für manche Server: localhost zu 127.0.0.1 wandeln, falls Verbindung verweigert wird
        if($host == "localhost") { $host = "127.0.0.1"; }

        try {
            $this->con = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $user, $pass, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ));
        } catch (PDOException $ex) {
            // Fehlermeldung nur im Notfall ausgeben
            die("Datenbankverbindung fehlgeschlagen. Bitte prüfen Sie Ihre config.php Einstellungen.");
        }
    }

    private function isDemoMode() {
        return (isset($_SESSION['admin_email']) && $_SESSION['admin_email'] == "demo@internetprofis.at");
    }

    private function triggerDemoNotification() {
        $_SESSION['demo_action_blocked'] = true;
    }

    public function query($query, $parameters = '', $limit = '') {
        $upperQuery = strtoupper(trim($query));
        if ($this->isDemoMode()) {
            $writingCommands = ['UPDATE', 'INSERT', 'DELETE', 'REPLACE', 'DROP', 'TRUNCATE', 'ALTER'];
            foreach ($writingCommands as $cmd) {
                if (strpos($upperQuery, $cmd) === 0) {
                    $this->triggerDemoNotification();
                    return true; 
                }
            }
        }
        try {
            $run_query = $this->con->prepare($query);
            if (!empty($limit)) {
                foreach ($limit as $key => $value) { $run_query->bindValue("$key", $value, PDO::PARAM_INT); }
            }
            if (!empty($parameters)) {
                foreach ($parameters as $key => $value) { $run_query->bindValue("$key", $value); }
            }
            if ($run_query->execute()) { return $run_query; } else { return false; }
        } catch (PDOException $ex) { return false; }
    }

    public function count($table, $parameters = "") {
        $where = "";
        $values = [];
        if (!empty($parameters)) {
            $i = 1;
            $where = "WHERE ";
            foreach ($parameters as $key => $value) {
                if ($i > 1) { $where .= " AND "; }
                $where .= "$key=:$key";
                $values[":$key"] = $value;
                $i++;
            }
        }
        try {
            $run_query = $this->con->prepare("SELECT * FROM $table $where");
            $run_query->execute($values);
            return $run_query->rowCount();
        } catch (PDOException $ex) { return 0; }
    }

    public function select($table, $parameters = "", $order = "") {
        $where = ""; $order_by = ""; $values = [];
        if (!empty($order)) { $order_by = "ORDER BY 1 $order"; }
        if (!empty($parameters)) {
            $i = 1; $where = "WHERE ";
            foreach ($parameters as $key => $value) {
                if ($i > 1) { $where .= " AND "; }
                $where .= "$key=:$key";
                $values[":$key"] = $value;
                $i++;
            }
        }
        try {
            $run_query = $this->con->prepare("SELECT * FROM $table $where $order_by");
            $run_query->execute($values);
            return $run_query;
        } catch (PDOException $ex) { return false; }
    }

    public function insert($table, $parameters = "") {
        if ($this->isDemoMode()) { $this->triggerDemoNotification(); return true; }
        if (empty($parameters)) return false;
        
        $fields = implode(",", array_keys($parameters));
        $placeholders = ":" . implode(",:", array_keys($parameters));
        $values = [];
        foreach($parameters as $k => $v) { $values[":$k"] = $v; }

        try {
            $run_query = $this->con->prepare("INSERT INTO $table ($fields) VALUES ($placeholders)");
            if ($run_query->execute($values)) { return $run_query; }
        } catch (PDOException $ex) { return false; }
    }

    public function update($table, $parameters, $where_p = "") {
        if ($this->isDemoMode()) { $this->triggerDemoNotification(); return true; }
        
        $fields = ""; $values = [];
        foreach ($parameters as $key => $value) {
            $fields .= "$key=:$key,";
            $values[":$key"] = $value;
        }
        $fields = rtrim($fields, ",");

        $where = "";
        if (!empty($where_p)) {
            $where = "WHERE ";
            foreach ($where_p as $key => $value) {
                $where .= "$key=:w_$key AND ";
                $values[":w_$key"] = $value;
            }
            $where = rtrim($where, " AND ");
        }
        try {
            $run_query = $this->con->prepare("UPDATE $table SET $fields $where");
            if ($run_query->execute($values)) { return $run_query; }
        } catch (PDOException $ex) { return false; }
    }

    public function delete($table, $parameters = '') {
        if ($this->isDemoMode()) { $this->triggerDemoNotification(); return true; }
        $where = ""; $values = [];
        if (!empty($parameters)) {
            $where = "WHERE ";
            foreach ($parameters as $key => $value) {
                $where .= "$key=:$key AND ";
                $values[":$key"] = $value;
            }
            $where = rtrim($where, " AND ");
        }
        try {
            $run_query = $this->con->prepare("DELETE FROM $table $where");
            if ($run_query->execute($values)) { return $run_query; }
        } catch (PDOException $ex) { return false; }
    }

    public function insert_log($admin_id, $work, $work_id, $status) {
        if ($this->isDemoMode()) { return true; }
        $date = date("F d, Y H:i:s");
        try {
            $run_query = $this->con->prepare("INSERT INTO admin_logs (admin_id,work,work_id,date,status) VALUES (:admin_id,:work,:work_id,:date,:status)");
            $run_query->execute([':admin_id'=>$admin_id, ':work'=>$work, ':work_id'=>$work_id, ':date'=>$date, ':status'=>$status]);
            return true;
        } catch (PDOException $ex) { return false; }
    }

    public function lastInsertId() {
        return $this->con->lastInsertId();
    }
}
$db = new Database();