<?php
@session_start();

if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login','_self');</script>";
    exit();
} else {
?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-comments"></i> Inbox Messages</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Inbox Messages</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">All Messages</h4>
            </div>
            <div class="card-body">
                <table id="bootstrap-data-table" class="table table-striped table-bordered links-table">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Message Content</th>
                            <th>Attachment</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $per_page = 10;
                        
                        // Fehlerbehebung: (int) Casting für PHP 8 Kompatibilität
                        $page = isset($_GET["inbox_conversations"]) ? (int)$_GET["inbox_conversations"] : 1;
                        if($page <= 0){ $page = 1; }

                        $start_from = ($page - 1) * $per_page;

                        // Einfache Query ohne komplexe Joins, um "fetch() on null" zu vermeiden
                       $get_inbox_sellers = $db->query(
    "SELECT * FROM inbox_sellers 
     WHERE NOT message_status='empty' 
     ORDER BY 1 DESC 
     LIMIT :limit OFFSET :offset", 
    "", 
    array(
        "limit" => (int)$per_page, 
        "offset" => (int)$start_from
    )
);
                        if($get_inbox_sellers){
                            while($row_inbox_sellers = $get_inbox_sellers->fetch()){
                                
                                $sender_id = $row_inbox_sellers->sender_id;
                                $receiver_id = $row_inbox_sellers->receiver_id;
                                $message_id = $row_inbox_sellers->message_id;
                                $message_group_id = $row_inbox_sellers->message_group_id;

                                // Nachrichtendetails holen
                                $select_inbox_message = $db->select("inbox_messages", array("message_id" => $message_id));
                                $row_inbox_message = $select_inbox_message->fetch();
                                
                                if($row_inbox_message){
                                    $message_file = $row_inbox_message->message_file;
                                    $message_desc = substr(strip_tags($row_inbox_message->message_desc), 0, 170);
                                    $message_date = $row_inbox_message->message_date;
                                } else {
                                    $message_file = "";
                                    $message_desc = "Message deleted or not found.";
                                    $message_date = "-";
                                }

                                // Sender Name holen
                                $select_sender = $db->select("sellers", array("seller_id" => $sender_id));
                                $row_sender = $select_sender->fetch();
                                $seller_user_name = ($row_sender) ? $row_sender->seller_user_name : "Unknown";

                                // Receiver Name holen
                                $select_receiver = $db->select("sellers", array("seller_id" => $receiver_id));
                                $row_receiver = $select_receiver->fetch();
                                $receiver_user_name = ($row_receiver) ? $row_receiver->seller_user_name : "Unknown";
                        ?>
                        
                        <tr onclick="location.href='index?single_inbox_message=<?= $message_group_id; ?>'" style="cursor:pointer;">
                            <td><?= htmlspecialchars($seller_user_name); ?></td>
                            <td><?= htmlspecialchars($receiver_user_name); ?></td>
                            <td width="300">
                                <?= (strlen($message_desc) > 120) ? htmlspecialchars(substr($message_desc, 0, 120)) . "..." : htmlspecialchars($message_desc); ?>
                            </td>
                            <td>
                                <?php if(empty($message_file)): ?>
                                    <span class="text-muted">No File Attachment</span>
                                <?php else: ?>
                                    <a href="#" class="text-primary">
                                        <i class="fa fa-download"></i> <?= htmlspecialchars($message_file); ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td><?= $message_date; ?></td>
                        </tr>
                        
                        <?php 
                            } // Ende while
                        } // Ende if get_inbox_sellers
                        ?>
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-4">
                    <ul class="pagination">
                        <?php
                        $query = $db->query("SELECT * FROM inbox_sellers WHERE NOT message_status='empty'");
                        $total_records = $query->rowCount();
                        $total_pages = ceil($total_records / $per_page);

                        echo "<li class='page-item'><a href='index?inbox_conversations=1' class='page-link'>First Page</a></li>";
                        
                        // Dynamische Seitenzahlen
                        $start_loop = max(1, $page - 3);
                        $end_loop = min($total_pages, $page + 3);

                        for ($i = $start_loop; $i <= $end_loop; $i++) {
                            $active = ($i == $page) ? "active" : "";
                            echo "<li class='page-item $active'><a href='index?inbox_conversations=$i' class='page-link'>$i</a></li>";
                        }

                        echo "<li class='page-item'><a href='index?inbox_conversations=$total_pages' class='page-link'>Last Page</a></li>";
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>
<div class="clearfix"></div>