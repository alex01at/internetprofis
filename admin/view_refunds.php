<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
?>

<div class="main-container">
<h4 class="mb-4"><i class="fa fa-undo"></i> Refunds</h4>
<div class="row"><!--- 2 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
  <div class="card"><!--- card Starts --->
    <div class="card-header"><!--- card-header Starts --->
      <h4 class="h4">Withdrawal (Widerruf) Requests</h4>
    </div>
    <div class="card-body">
      <div class="table-responsive">
      <table class="table table-bordered">
      <thead>
      <tr>
      <th>#Num</th>
      <th>User</th>
      <th>Order Number</th>
      <th>Full Name</th>
      <th>Email</th>
      <th>Ordered</th>
      <th>Received</th>
      <th>Reason</th>
      <th>Status</th>
      <th>Date</th>
      <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      <?php
        $i = 0;

        $get_refunds = $db->select("withdrawal_notices order By 1 DESC");

        while($row_refunds = $get_refunds->fetch()){

        $id = $row_refunds->id;
        $seller_id = $row_refunds->seller_id;
        $order_number = $row_refunds->order_number;
        $full_name = $row_refunds->full_name;
        $address = $row_refunds->address;
        $email = $row_refunds->email;
        $ordered_date = $row_refunds->ordered_date;
        $received_date = $row_refunds->received_date;
        $reason = $row_refunds->reason;
        $status = $row_refunds->status;
        $date = $row_refunds->date;

        $select_seller = $db->select("sellers",array("seller_id" => $seller_id));
        $row_seller = $select_seller->fetch();
        $seller_user_name = @$row_seller->seller_user_name;

        $i++;

      ?>
      <tr>
        <td><?= $i; ?></td>
        <td><a href="index?single_seller=<?= $seller_id; ?>" class="text-primary"><?= $seller_user_name; ?></a></td>
        <td><?= htmlspecialchars($order_number); ?></td>
        <td><?= htmlspecialchars($full_name); ?></td>
        <td><?= htmlspecialchars($email); ?></td>
        <td><?= htmlspecialchars($ordered_date); ?></td>
        <td><?= htmlspecialchars($received_date); ?></td>
        <td>
          <?php if(!empty($reason)){ ?>
          <a href="#" data-toggle="modal" data-target="#reason-modal-<?= $id; ?>"><i class="fa fa-eye"></i> View</a>
          <div id="reason-modal-<?= $id; ?>" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Reason / Address - Request #<?= $id; ?></h5>
                  <button class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                  <p><strong>Address:</strong><br><?= nl2br(htmlspecialchars($address)); ?></p>
                  <p><strong>Reason:</strong><br><?= nl2br(htmlspecialchars($reason)); ?></p>
                </div>
              </div>
            </div>
          </div>
          <?php }else{ echo "-"; } ?>
        </td>
        <td>
          <?php if($status == 'processed'){ ?>
          <span class="badge badge-success">Processed</span>
          <?php }else{ ?>
          <span class="badge badge-warning">New</span>
          <?php } ?>
        </td>
        <td><?= $date; ?></td>
        <td>
          <?php if($status != 'processed'){ ?>
          <a href="#" onclick="alert_confirm('Mark this request as processed?','index.php?mark_refund_processed=<?= $id; ?>');"><i class="fa fa-check"></i> Mark Processed</a>
          &nbsp;|&nbsp;
          <?php } ?>
          <a href="#" onclick="alert_confirm('Do you really want to delete this request permanently.','index.php?delete_refund=<?= $id; ?>');"><i class="fa fa-trash"></i> Delete</a>
        </td>
      </tr>
      <?php } ?>
      </tbody>
      </table>
      </div>
    </div><!--- card-body Ends --->
  </div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div>
<?php } ?>
