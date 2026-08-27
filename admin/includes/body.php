<?php
// 1. Basis-Routen, die immer ohne extra Rechte (außer eingeloggt) erreichbar sind
$base_routes = [
    'dashboard'       => 'dashboard.php',
    'credits'         => 'includes/credits.php',
    'change_language' => 'change_language.php',
    'view_withdrawals'=> 'view_withdrawals.php',
    'user_profile'    => 'user_profile.php'
];

// 2. Rechtebasierte Routen (Modul => [GET-Parameter => Dateipfad])
$protected_routes = [
    'a_admins' => [
        'admin_logs'      => 'admin_logs.php',
        'delete_log'      => 'delete/delete_log.php',
        'delete_all_logs' => 'delete/delete_all_logs.php',
        'insert_user'     => 'insert/insert_user.php',
        'view_users'      => 'view/view_users.php',
        'delete_user'     => 'delete/delete_user.php',
        'edit_rights'     => 'edit/edit_rights.php',
    ],
    'a_countries_permission' => [ // Erstellt, da 'countries' vorher ungeschützt war
        'countries'       => 'countries.php',
        'delete_country'  => 'delete/delete_country.php',
        'insert_country'  => 'insert/insert_country.php',
    ],
    'a_settings' => [
        'general'          => 'includes/g_settings.php',
        'seo'              => 'includes/seo_settings.php',
        'payment'          => 'includes/payment_settings.php',
        'mail-server'      => 'includes/mail-server.php',
        'try'              => 'try.php',
        'insert_card'      => 'insert/insert_card.php',
        'delete_card'      => 'delete/delete_card.php',
        'edit_card'        => 'edit/edit_card.php',
        'insert_box'       => 'insert/insert_box.php',
        'delete_box'       => 'delete/delete_box.php',
        'edit_box'         => 'edit/edit_box.php',
        'theme_settings'   => 'includes/theme_settings.php',
        'color_settings'   => 'includes/color_settings.php',
        'logo_settings'    => 'includes/logo_settings.php',
        'edit_link'        => 'edit/edit_link.php',
        'delete_link'      => 'delete/delete_link.php',
        'payment_settings' => 'payment_settings.php',
        'get_provider_id'  => 'get_provider_id.php',
        'insert_home_slide'=> 'insert/insert_home_slide.php',
        'delete_home_slide'=> 'delete/delete_home_slide.php',
        'edit_home_slide'  => 'edit/edit_home_slide.php',
        'mail_settings'    => 'mail_settings.php',
        'email_templates'  => 'email_templates.php',
        'api_settings'     => 'includes/api_settings.php',
        'mail-templates'   => 'includes/email_templates.php',
        'app_update'       => 'app_update.php',
    ],
    'a_plugins' => [
        'plugins'            => 'plugins.php',
        'update_plugin'      => 'update_plugin.php',
        'add_plugin'         => 'add_plugin.php',
        'delete_plugin'      => 'delete/delete_plugin.php',
        'activate_plugin'    => 'activate_plugin.php',
        'deactivate_plugin'  => 'deactivate_plugin.php',
    ],
    'a_pages' => [
        'pages'       => 'includes/pages.php',
        'insert_page' => 'insert/insert_page.php',
        'edit_page'   => 'edit/edit_page.php',
        'delete_page' => 'delete/delete_page.php',
    ],
    'a_blog' => [
        'blog_categories'        => 'view/view_blog_categories.php',
        'insert_blog_categories' => 'insert/insert_blog_categories.php',
        'edit_blog_cat'          => 'edit/edit_blog_categories.php',
        'delete_blog_cat'        => 'delete/delete_blog_categories.php',
        'blog'                   => 'view/view_blog_post.php',
        'insert_blog'            => 'insert/insert_blog_posts.php',
        'edit_blog'              => 'edit/edit_blog_posts.php',
        'delete_blog'            => 'delete/delete_blog_posts.php',
        'blog_comments'          => 'view/view_blog_comments.php',
        'delete_blog_comment'    => 'delete/delete_blog_comments.php',
    ],
    'a_feedback' => [
        'ideas'          => 'view/view_ideas.php',
        'delete_idea'    => 'delete/delete_ideas.php',
        'comments'       => 'view/view_comments.php',
        'delete_comment' => 'delete/delete_comments.php',
    ],
    'a_proposals' => [
        'view_proposals'          => 'view/view_proposals.php',
        'view_proposals_active'   => 'view/view_proposals_active.php',
        'view_proposals_featured' => 'view/view_proposals_featured.php',
        'view_proposals_pending'  => 'view/view_proposals_pending.php',
        'view_proposals_paused'   => 'view/view_proposals_paused.php',
        'view_proposals_trash'    => 'view/view_proposals_trash.php',
        'pause_proposal'          => 'pause_proposal.php',
        'filter_proposals'        => 'filter_proposals.php',
        'feature_proposal'        => 'feature_proposal.php',
        'remove_feature_proposal' => 'remove_feature_proposal.php',
        'toprated_proposal'       => 'toprated_proposal.php',
        'removetoprated_proposal' => 'removetoprated_proposal.php',
        'unpause_proposal'        => 'includes/unpause_proposal.php',
        'move_to_trash'           => 'includes/move_to_trash.php',
        'decline_proposal'        => 'decline_proposal.php',
        'approve_proposal'        => 'approve_proposal.php',
        'submit_modification'     => 'submit_modification.php',
        'restore_proposal'        => 'restore_proposal.php',
        'delete_proposal'         => 'delete/delete_proposal.php',
    ],
    'a_accounting' => [
        'sales'          => 'sales.php',
        'expenses'       => 'expenses.php',
        'delete_expense' => 'delete/delete_expense.php',
    ],
    'a_payouts' => [
        'payouts'                => 'payouts.php',
        'approve_payout'         => 'approve_payout.php',
        'decline_payout'         => 'decline_payout.php',
        'completed_transactions' => 'completed_transactions.php',
    ],
    'a_reports' => [
        'order_reports'          => 'order_reports.php',
        'message_reports'        => 'message_reports.php',
        'proposal_reports'       => 'proposal_reports.php',
        'delete_order_report'    => 'delete/delete_order_report.php',
        'delete_message_report'  => 'delete/delete_message_report.php',
        'delete_proposal_report' => 'delete/delete_proposal_report.php',
    ],
    'a_inbox' => [
        'inbox_conversations' => 'inbox_conversations.php',
        'single_inbox_message'=> 'includes/single_inbox_message.php',
    ],
    'a_reviews' => [
        'insert_review'        => 'insert/insert_review.php',
        'view_buyer_reviews'   => 'view/view_buyer_reviews.php',
        'delete_buyer_review'  => 'delete/delete_buyer_review.php',
        'view_seller_reviews'  => 'view/view_seller_reviews.php',
        'delete_seller_review' => 'delete/delete_seller_review.php',
    ],
    'a_buyer_requests' => [
        'buyer_requests'    => 'buyer_requests.php',
        'delete_request'    => 'delete/delete_request.php',
        'approve_request'   => 'approve_request.php',
        'unapprove_request' => 'unapprove_request.php',
    ],
    'a_restricted_words' => [
        'insert_word' => 'insert/insert_word.php',
        'view_words'  => 'view/view_words.php',
        'delete_word' => 'delete/delete_word.php',
        'edit_word'   => 'edit/edit_word.php',
    ],
    'a_alerts' => [
        'view_notifications'  => 'view/view_notifications.php',
        'delete_notification' => 'delete/delete_notification.php',
    ],
    'a_cats' => [
        'insert_cat'       => 'insert/insert_cat.php',
        'categories'       => 'categories.php',
        'delete_cat'       => 'delete/delete_cat.php',
        'edit_cat'         => 'edit/edit_cat.php',
        'insert_child_cat' => 'insert/insert_child_cat.php',
        'children'         => 'children.php',
        'delete_child_cat' => 'delete/delete_child_cat.php',
        'edit_child_cat'   => 'edit/edit_child_cat.php',
        'view_child_cats'  => 'view/view_child_cats.php',
        'view_cats'        => 'view/view_cats.php',
    ],
    'a_delivery_times' => [
        'insert_delivery_time'=> 'insert/insert_delivery_time.php',
        'view_delivery_times' => 'view/view_delivery_times.php',
        'delete_delivery_time'=> 'delete/delete_delivery_time.php',
        'edit_delivery_time'  => 'edit/edit_delivery_time.php',
    ],
    'a_seller_languages' => [
        'insert_seller_language'=> 'insert/insert_seller_language.php',
        'view_seller_languages' => 'view/view_seller_languages.php',
        'delete_seller_language'=> 'delete/delete_seller_language.php',
        'edit_seller_language'  => 'edit/edit_seller_language.php',
    ],
    'a_seller_skills' => [
        'insert_seller_skill'=> 'insert/insert_seller_skill.php',
        'view_seller_skills' => 'view/view_seller_skills.php',
        'delete_seller_skill'=> 'delete/delete_seller_skill.php',
        'edit_seller_skill'  => 'edit/edit_seller_skill.php',
    ],
    'a_seller_levels' => [
        'view_seller_levels' => 'view/view_seller_levels.php',
        'edit_seller_level'   => 'edit/edit_seller_level.php',
    ],
    'a_customer_support' => [
        'customer_support_settings'  => 'customer_support_settings.php',
        'view_support_requests'       => 'view/view_support_requests.php',
        'view_support_requests_closed'=> 'view/view_support_requests_closed.php',
        'single_request'              => 'single_request.php',
        'insert_enquiry_type'         => 'insert/insert_enquiry_type.php',
        'view_enquiry_types'          => 'view/view_enquiry_types.php',
        'delete_enquiry_type'         => 'delete/delete_enquiry_type.php',
        'edit_enquiry_type'           => 'edit/edit_enquiry_type.php',
    ],
    'a_coupons' => [
        'insert_coupon' => 'insert/insert_coupon.php',
        'view_coupons'  => 'view/view_coupons.php',
        'delete_coupon' => 'delete/delete_coupon.php',
        'edit_coupon'   => 'edit/edit_coupon.php',
    ],
    'a_slides' => [
        'insert_slide' => 'insert/insert_slide.php',
        'view_slides'  => 'view/view_slides.php',
        'delete_slide' => 'delete/delete_slide.php',
        'edit_slide'   => 'edit/edit_slide.php',
    ],
    'a_terms' => [
        'insert_term' => 'insert/insert_term.php',
        'view_terms'  => 'view/view_terms.php',
        'delete_term' => 'delete/delete_term.php',
        'edit_term'   => 'edit/edit_term.php',
    ],
    'a_sellers' => [
        'view_sellers'   => 'view/view_sellers.php',
        'single_seller'  => 'single_seller.php',
        'seller_login'   => 'seller_login.php',
        'update_balance' => 'update_balance.php',
        'unblock_seller' => 'includes/unblock_seller.php',
        'ban_seller'     => 'includes/ban_seller.php',
        'verify_email'   => 'verify_email.php',
    ],
    'a_orders' => [
        'view_orders'   => 'view/view_orders.php',
        'filter_orders' => 'includes/filter_orders.php',
        'single_order'  => 'single_order.php',
        'cancel_order'  => 'cancel_order.php',
    ],
    'a_referrals' => [
        'view_referrals'           => 'view/view_referrals.php',
        'approve_referral'         => 'approve_referral.php',
        'decline_referral'         => 'decline_referral.php',
        'view_proposal_referrals'  => 'view/view_proposal_referrals.php',
        'approve_proposal_referral'=> 'approve_proposal_referral.php',
        'decline_proposal_referral'=> 'decline_proposal_referral.php',
    ],
    'a_files' => [
        'view_proposals_files'    => 'view/view_proposals_files.php',
        'view_s3_proposals_files' => 'view/view_s3_proposals_files.php',
        'delete_proposal_file'    => 'delete/delete_proposal_file.php',
        'view_inbox_files'        => 'view/view_inbox_files.php',
        'inbox_files_pagination'  => 'inbox_files_pagination.php',
        'delete_inbox_file'       => 'delete/delete_inbox_file.php',
        'view_order_files'        => 'view/view_order_files.php',
        'order_files_pagination'  => 'order_files_pagination.php',
        'delete_order_file'       => 'delete/delete_order_file.php',
    ],
    'a_knowledge_bank' => [
        'insert_article'    => 'insert/insert_article.php',
        'view_articles'     => 'view/view_articles.php',
        'delete_article'    => 'delete/delete_article.php',
        'edit_article'      => 'edit/edit_article.php',
        'insert_article_cat'=> 'insert/insert_article_cat.php',
        'view_article_cats' => 'view/view_article_cats.php',
        'delete_article_cat'=> 'delete/delete_article_cat.php',
        'edit_article_cat'  => 'edit/edit_article_cat.php',
    ],
    'a_currencies' => [
        'insert_currency' => 'insert/insert_currency.php',
        'view_currencies' => 'view/view_currencies.php',
        'edit_currency'   => 'edit/edit_currency.php',
        'delete_currency' => 'delete/delete_currency.php',
    ],
    'a_languages' => [
        'insert_language'  => 'insert/insert_language.php',
        'edit_language'    => 'edit/edit_language.php',
        'delete_language'  => 'delete/delete_language.php',
        'language_settings'=> 'language_settings.php',
        'view_languages'   => 'view/view_languages.php',
    ]
];

// 3. Spezialfälle für verschachtelte oder kombinierte Rechte (z.B. Video-Plugin & Payment)
if (isset($_GET['approve_moneygram']) && isset($paymentGateway) && $paymentGateway == 1 && isset($a_payouts) && $a_payouts == 1) {
    include("../plugins/paymentGateway/admin/approve_moneygram.php");
}

if (isset($videoPlugin) && $videoPlugin == 1 && isset($a_video_schedules) && $a_video_schedules == 1) {
    $video_routes = [
        'insert_schedule' => '../plugins/videoPlugin/admin/insert_schedule.php',
        'view_schedules'  => '../plugins/videoPlugin/admin/view_schedules.php',
        'edit_schedule'   => '../plugins/videoPlugin/admin/edit_schedule.php',
        'delete_schedule' => '../plugins/videoPlugin/admin/delete_schedule.php',
    ];
    foreach ($video_routes as $param => $file) {
        if (isset($_GET[$param])) {
            include($file);
        }
    }
}

// 4. Der eigentliche Router-Prozess
// Wir gehen alle per GET übergebenen Parameter durch
foreach ($_GET as $param => $value) {
    
    // Prüfe Basis-Routen
    if (isset($base_routes[$param])) {
        include($base_routes[$param]);
        break; // Sobald eine passende Datei gefunden wurde, stoppen wir
    }

    // Prüfe rechtebasierte Routen
    foreach ($protected_routes as $permission_var => $routes) {
        // Wenn das Recht existiert, auf 1 steht UND der GET-Parameter registriert ist
        if (isset($$permission_var) && $$permission_var == 1 && isset($routes[$param])) {
            include($routes[$param]);
            break 2; // Bricht beide Schleifen ab
        }
    }
}