<?php 
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login.php','_self');</script>";
}else{
    if(isset($_GET['delete_country'])){
        $ids = $_GET['delete_country']; // IDs der zu löschenden Länder als Array erhalten
        
        // Schleife über die IDs, um jedes Land einzeln zu löschen
        foreach($ids as $id){
            $delete_country = $db->delete("countries", array("id" => $id)); 
        }
        
        // Überprüfen, ob alle Länder erfolgreich gelöscht wurden
        if($delete_country){
            echo "<script>alert_success('Selected Countries Have Been Deleted.','index?countries');</script>";
        } else {
            echo "<script>alert_error('Failed to delete selected countries.');</script>";
        }
    }
    
}
 ?>