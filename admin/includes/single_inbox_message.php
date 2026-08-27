<?php
@session_start();

if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login','_self');</script>";
    exit();
}

$single_messages_id = $input->get('single_inbox_message');

// --- 1. SCHIMPFWORTER-LOGIK ---
// Hier kannst du Wörter definieren, die hervorgehoben werden sollen (z.B. Skype, Email, @, etc.)
$bad_words_string = "skype,whatsapp,email,paypal,pay,überweisung,arsch,pimmel,@,.com,.de"; // Beispiel-Liste
$bad_words_array = explode(",", $bad_words_string);

/**
 * Funktion zum Hervorheben bösartiger Wörter
 */
function highlightBadWords($text, $words) {
    foreach ($words as $word) {
        $word = trim($word);
        if (!empty($word)) {
            // preg_replace für Case-Insensitive Suche und exakte Treffer
            $text = preg_replace("/($word)/i", "<span class='bad-word-highlight'>$1</span>", $text);
        }
    }
    return $text;
}
?>

<style>
    .chat-container { background: #f4f7f6; padding: 20px; border-radius: 8px; max-height: 700px; overflow-y: auto; }
    .message-item { margin-bottom: 25px; display: flex; flex-direction: column; }
    .message-header { display: flex; align-items: center; margin-bottom: 8px; }
    .message-avatar { width: 40px; height: 40px; border-radius: 50%; margin-right: 12px; }
    .message-bubble { background: #fff; padding: 15px 20px; border-radius: 0 15px 15px 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); max-width: 85%; }
    
    /* --- OPTISCHES HIGHLIGHTING --- */
    .bad-word-highlight {
        background-color: #fff3cd; /* Sanftes Gelb */
        color: #856404;
        border-bottom: 2px dashed #ffeeba;
        font-weight: bold;
        padding: 0 2px;
        border-radius: 2px;
    }
    
    .offer-card { background: #f8f9ff; border: 1px solid #e0e6ff; border-left: 4px solid #4a6cf7; padding: 15px; border-radius: 8px; margin-top: 10px; }
    .price-tag { color: #28a745; font-weight: 700; }
</style>

<div class="main-container">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h4 class="m-0 font-weight-bold">
                <i class="fa fa-eye text-danger mr-2"></i> Review Conversation
            </h4>
            <a href="index?inbox_conversations" class="btn btn-light border btn-sm">Back</a>
        </div>
        
        <div class="card-body chat-container">
            <?php
            $query = "
                SELECT m.*, s.seller_user_name, s.seller_image,
                       o.amount, o.status as offer_status, o.description as offer_desc, o.delivery_time, o.order_id,
                       p.proposal_title, p.proposal_img1
                FROM inbox_messages m
                JOIN sellers s ON m.message_sender = s.seller_id
                LEFT JOIN messages_offers o ON m.message_offer_id = o.offer_id
                LEFT JOIN proposals p ON o.proposal_id = p.proposal_id
                WHERE m.message_group_id = :group_id
                ORDER BY m.message_id ASC";

            $get_messages = $db->query($query, array("group_id" => $single_messages_id));

            while($row = $get_messages->fetch()){
                $sender_name = htmlspecialchars($row->seller_user_name);
                $sender_img = !empty($row->seller_image) ? "../user_images/" . $row->seller_image : "../user_images/empty-image.png";
                
                // Zuerst HTML-Sicher machen, dann die Bad-Words hervorheben
                $clean_message = htmlspecialchars($row->message_desc);
                $highlighted_message = highlightBadWords($clean_message, $bad_words_array);
                
                $message_date = $row->message_date;
            ?>

            <div class="message-item">
                <div class="message-header">
                    <img src="<?= $sender_img; ?>" class="message-avatar">
                    <span class="font-weight-bold small"><?= $sender_name; ?></span>
                    <span class="ml-2 text-muted small"><?= $message_date; ?></span>
                </div>
                
                <div class="message-bubble">
                    <div class="message-content"><?= $highlighted_message; ?></div>
                    
                    <?php if(!empty($row->message_file)): ?>
                        <div class="mt-2 small">
                            <i class="fa fa-paperclip"></i> <a href="../conversations/conversations_files/<?= $row->message_file; ?>"><?= $row->message_file; ?></a>
                        </div>
                    <?php endif; ?>

                    <?php if($row->message_offer_id != 0): ?>
                        <div class="offer-card mt-3">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="<?= getImageUrl2("proposals","proposal_img1",$row->proposal_img1); ?>" class="img-fluid">
                                </div>
                                <div class="col-md-10">
                                    <h6 class="m-0 font-weight-bold"><?= htmlspecialchars($row->proposal_title); ?> <span class="price-tag float-right"><?= showPrice($row->amount); ?></span></h6>
                                    <p class="small text-muted"><?= highlightBadWords(htmlspecialchars($row->offer_desc), $bad_words_array); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php } ?>
        </div>
    </div>
</div>