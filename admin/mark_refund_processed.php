<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

if(isset($_GET['mark_refund_processed'])){

	$id = $input->get('mark_refund_processed');
	$update_refund = $db->update("withdrawal_notices",array("status" => 'processed'),array("id" => $id));

	if($update_refund){
		echo "<script>
		swal({
			type: 'success',
			text: 'Request marked as processed!',
			timer: 3000,
			onOpen: function(){
			 swal.showLoading();
			}
		}).then(function(){
			window.open('index?view_refunds','_self');
		});
		</script>";
	}

}

}
