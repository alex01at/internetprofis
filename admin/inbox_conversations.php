<?php
@session_start();

if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login','_self');</script>";
    exit();
}

// 1. Lösch-Logik
if (isset($_POST['delete_message'])) {
    $delete_id = (int)$_POST['message_id'];
    $db->delete("inbox_messages", array("message_id" => $delete_id));
    $db->delete("inbox_sellers", array("message_id" => $delete_id));
    echo "<script>alert('Nachricht gelöscht.');</script>";
    echo "<script>window.open('index?inbox_conversations=".(isset($_GET['inbox_conversations']) ? (int)$_GET['inbox_conversations'] : 1)."','_self');</script>";
}
?>
<div class="main-container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">All Messages</strong>
                </div>
                <div class="card-body">
                    <table id="bootstrap-data-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Message Content</th>
                                <th>Attachment</th>
                                <th>Updated</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $per_page = 10;
                            $page = isset($_GET["inbox_conversations"]) ? (int)$_GET["inbox_conversations"] : 1;
                            if($page <= 0){ $page = 1; }
                            $start_from = ($page - 1) * $per_page;

                            $get_inbox_sellers = $db->query("
                                SELECT i.*, 
                                       s.seller_user_name AS sender_name, 
                                       r.seller_user_name AS receiver_name,
                                       m.message_desc, m.message_file, m.message_date
                                FROM inbox_sellers i
                                LEFT JOIN sellers s ON i.sender_id = s.seller_id
                                LEFT JOIN sellers r ON i.receiver_id = r.seller_id
                                LEFT JOIN inbox_messages m ON i.message_id = m.message_id
                                WHERE NOT i.message_status = 'empty'
                                ORDER BY i.message_id DESC 
                                LIMIT :limit OFFSET :offset", 
                                "", 
                                array("limit" => (int)$per_page, "offset" => (int)$start_from)
                            );

                            if($get_inbox_sellers){
                                while($row = $get_inbox_sellers->fetch()){
                                    $message_id = $row->message_id;
                                    $group_id = $row->message_group_id;
                                    $clean_desc = strip_tags($row->message_desc ?? '');
                            ?>
                            
                            <tr>
                                <td onclick="location.href='index?single_inbox_message=<?= $group_id; ?>'" style="cursor:pointer;"><?= htmlspecialchars($row->sender_name ?? 'Deleted'); ?></td>
                                <td onclick="location.href='index?single_inbox_message=<?= $group_id; ?>'" style="cursor:pointer;"><?= htmlspecialchars($row->receiver_name ?? 'Deleted'); ?></td>
                                <td onclick="location.href='index?single_inbox_message=<?= $group_id; ?>'" style="cursor:pointer;" width="300">
                                    <?= (strlen($clean_desc) > 120) ? htmlspecialchars(substr($clean_desc, 0, 120)) . "..." : htmlspecialchars($clean_desc); ?>
                                </td>
                                <td>
                                    <?php if(!empty($row->message_file)): ?>
                                        <a href="../attachments/<?= htmlspecialchars($row->message_file); ?>" target="_blank" class="text-primary">
                                            <i class="fa fa-download"></i> <?= htmlspecialchars($row->message_file); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No Attachment</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $row->message_date; ?></td>
                                <td class="text-center">
                                    <form method="post" onsubmit="return confirm('Bist du sicher?');">
                                        <input type="hidden" name="message_id" value="<?= $message_id; ?>">
                                        <button type="submit" name="delete_message" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
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
                            $total_records = $db->query("SELECT COUNT(*) FROM inbox_sellers WHERE NOT message_status='empty'")->fetchColumn();
                            $total_pages = ceil($total_records / $per_page);

                            echo "<li class='page-item'><a href='index?inbox_conversations=1' class='page-link'>First</a></li>";
                            
                            $start = max(1, $page - 3);
                            $end = min($total_pages, $page + 3);

                            for ($i = $start; $i <= $end; $i++) {
                                $active = ($i == $page) ? "active" : "";
                                echo "<li class='page-item $active'><a href='index?inbox_conversations=$i' class='page-link'>$i</a></li>";
                            }

                            echo "<li class='page-item'><a href='index?inbox_conversations=$total_pages' class='page-link'>Last</a></li>";
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>