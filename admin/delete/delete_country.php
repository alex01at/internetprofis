<?php 
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login.php','_self');</script>";
}else{
if(isset($_GET['delete_country'])){
$id = $input->get('delete_country');
$delete_word = $db->delete("countries",array("id"=>$id)); 
if($delete_word){
echo "<script>alert_success('Country Has Been Deleted.','index?countries');</script>";
}
}
?>
<?php } ?>