<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{

  if(isset($_GET['delete_layout_block'])){

    $block_id = $input->get('delete_layout_block');

    // Release any cards/boxes in this block rather than deleting content.
    $db->update("home_cards",array("block_id" => null),array("block_id" => $block_id));
    $db->update("section_boxes",array("block_id" => null),array("block_id" => $block_id));

    $delete_block = $db->delete("home_layout_blocks",array('id' => $block_id));

    if($delete_block){
      echo "<script>
      swal({
        type: 'success',
        text: 'Homepage section deleted successfully!',
        timer: 3000,
        onOpen: function(){
         swal.showLoading();
        }
      }).then(function(){
        window.open('index?theme_settings','_self');
      });
      </script>";
    }

  }

}
