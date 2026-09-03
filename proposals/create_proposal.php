<?php
/**
 * create_proposal.php - Optimiert & SQL-Fix (language_id & level_id)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../includes/db.php");

// 1. Zugriffsschutz
if(!isset($_SESSION['seller_user_name'])){
    header("Location: ../login");
    exit();
}

// 2. Daten laden
$login_seller_user_name = $_SESSION['seller_user_name'];
$get_general_settings = $db->select("general_settings");
$row_general_settings = $get_general_settings->fetch();

// Seller-Daten inkl. Level und Sprache abrufen
$select_login_seller = $db->select("sellers", ["seller_user_name" => $login_seller_user_name]);
$row_login_seller = $select_login_seller->fetch();

if(!$row_login_seller) {
    header("Location: ../logout");
    exit();
}

$login_seller_id = $row_login_seller->seller_id;
$login_seller_level = $row_login_seller->seller_level; 
$login_seller_language = $row_login_seller->seller_language; // DAS wird für language_id benötigt!
$seller_verification = $row_login_seller->seller_verification;

// Fallbacks für SQL-Sicherheit (Verhindert "cannot be null" Fehler)
if(empty($login_seller_level)) $login_seller_level = 1;
if(empty($login_seller_language)) $login_seller_language = 1;

// Revisionen Logik
$enable_unlimited_revisions = $row_general_settings->enable_unlimited_revisions ?? 0;
$revisions = range(0, 10);
if($enable_unlimited_revisions == 1){
    $revisions['unlimited'] = "Unlimited Revisions";
}
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
    <title><?= htmlspecialchars($site_name ?? 'Marketplace'); ?> - <?= $lang["titles"]["create_proposal"] ?? 'Create Proposal'; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- CSS Assets -->
    <link href="../styles/bootstrap.css" rel="stylesheet">
    <link href="../styles/summernote-0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet">
    <link href="../styles/user_nav_styles.css" rel="stylesheet">
    <link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../styles/tagsinput.css" rel="stylesheet">
    <link href="../styles/sweat_alert.css" rel="stylesheet">
    <link href="../styles/croppie.css" rel="stylesheet">
    <link href="../styles/create-proposal.css" rel="stylesheet">

    <!-- JS Assets: Popper vor Bootstrap! -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/sweat_alert.js"></script>
    <script src="../js/croppie.js"></script>
</head>
<body class="is-responsive">

<?php 
require_once("../includes/user_header.php"); 

if($seller_verification != "ok"): ?>
    <div class="alert alert-danger rounded-0 mt-0 text-center shadow-sm">
        <i class="fa fa-envelope-o"></i> Please confirm your email address to create a proposal.
    </div>
<?php else: ?>

    <?php require_once("sections/createProposalNav.php"); ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11 col-md-12">
                <div class="tab-content card card-body shadow-sm border-0">
                    <div class="tab-pane fade show active" id="overview">
                        <!-- Variablen für den Insert in overview.php: $login_seller_level, $login_seller_language -->
                        <?php include("sections/create/overview.php"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal für Bild-Zuschnitt -->
    <div id="insertimageModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Crop Image</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <div id="image_demo"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="img_type" value="">
                    <button class="btn btn-success crop_image">Crop & Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $lang['button']['close']; ?></button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function(){

        // --- Referral Commission Toggle ---
        function handleReferralVisibility() {
            let referralStatus = $(".proposal_enable_referrals").val();
            if($(".proposal_enable_referrals").is(':radio')) {
                referralStatus = $("input[name='proposal_enable_referrals']:checked").val();
            }

            if(referralStatus === "yes") {
                $(".proposal_referral_money").fadeIn().find("input").attr("required", true);
            } else {
                $(".proposal_referral_money").hide().find("input").removeAttr("required");
            }
        }

        handleReferralVisibility();
        $(document).on('change', '.proposal_enable_referrals', function() {
            handleReferralVisibility();
        });

        // --- Kategorien AJAX ---
        $("#category").change(function(){
            let category_id = $(this).val();
            if(category_id != "") {
                $.ajax({
                    url: "fetch_subcategory",
                    method: "POST",
                    data: { category_id: category_id },
                    success: function(data){
                        $("#sub-category").html(data).fadeIn();
                    }
                });
            }
        });

        // --- Croppie Setup ---
        const $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: { width: 540, height: 300, type: 'square' },
            boundary: { width: 600, height: 400 }
        });

        $(document).on('change', 'input[type=file]:not(#v_file)', function(){
            const reader = new FileReader();
            const name = $(this).attr('name');
            reader.onload = function (event) {
                $image_crop.croppie('bind', { url: event.target.result });
            }
            reader.readAsDataURL(this.files[0]);
            $('input[name=img_type]').val(name);
            $('#insertimageModal').modal('show');
        });

        $('.crop_image').click(function(){
            const name = $('input[name=img_type]').val();
            $image_crop.croppie('result', { type: 'canvas', size: 'viewport' }).then(function(response){
                $.ajax({
                    url: "crop_upload",
                    type: "POST",
                    data: { image: response },
                    success: function(data){
                        $('#insertimageModal').modal('hide');
                        $('input[type=hidden][name='+ name +']').val(data);
                        swal("Success!", "Image has been cropped.", "success");
                    }
                });
            });
        });

        // --- Summernote ---
        if($.isFunction($.fn.summernote)) {
            $('textarea[name="proposal_desc"]').summernote({
                placeholder: 'Describe your service...',
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']]
                ]
            });
        }
    });
    </script>

<?php endif; ?>

<?php require_once("../includes/footer.php"); ?>
</body>
</html>