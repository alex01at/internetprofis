<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{

  if(isset($_GET['delete_refund'])){

    $id = $input->get('delete_refund');
    $delete_refund = $db->delete("withdrawal_notices",array('id' => $id));
    if($delete_refund){
      echo "<script>
      swal({
        type: 'success',
        text: 'Request deleted successfully!',
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
