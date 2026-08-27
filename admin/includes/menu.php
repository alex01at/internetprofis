<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{ 
    ?>
<div class="left-side-bar">
	<div class="brand-logo">
		<a href="index?dashboard">
			<?= $site_name; ?> ADMIN
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu icon-style-2">
			<ul id="accordion-menu">
				<li>
					<a href="index?dashboard" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-chat3"></span><span class="mtext">Dashboard</span>
					</a>
				</li>
				<li>
					<a href="index?view_proposals" class="dropdown-toggle no-arrow">
						<span class="micon icon-copy fa fa-file"></span><span class="mtext">Proposals/Services</span></a>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon dw dw-settings2"></span><span class="mtext">Settings</span>
					</a>
					<ul class="submenu">
						<li><a href="index?general">General Settings</a></li>
						<li><a href="index?seo">Seo Settings</a></li>
						<li><a href="index?payment">Payment Settings</a></li>
						<li><a href="index?mail-server">Mail Server Settings</a></li>
						<li><a href="index?mail-templates">Mail Templates</a></li>
						<li><a href="index?api_settings">API Settings</a></li>
						<li><a href="index?view_words">Restricted Words</a></li>
						<li><a href="index?countries">Countries</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon dw dw-library"></span><span class="mtext">Theme</span>
					</a>
					<ul class="submenu">
						<li><a href="index?theme_settings">Basic Settings</a></li>
						<li><a href="index?color_settings">Color Settings</a></li>
						<li><a href="index?logo_settings">Logo Settings</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon dw dw-apartment"></span><span class="mtext">Content</span>
					</a>
					<ul class="submenu">
						<li><a href="index?categories"><span class="mtext">Categories</span></a></li>
						<li><a href="index?children"><span class="mtext">Sub Categories</span></a></li>
						<li><a href="index?pages">Pages</a></li>
						<li><a href="index?view_terms">Terms & Conditions</a></li>
						<li><a href="index?view_buyer_reviews">Reviews</a></li>
						<li><a href="index?view_delivery_times">Delivery Times</a></li>
						<li><a href="index?view_articles">Knowledge Bank</a></li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="mtext">Feedback/Ideas</span>
							</a>
							<ul class="submenu child">
								<li><a href="index?ideas">Ideas</a></li>
								<li><a href="index?comments">Comments</a></li>
								<li><a href="index?view_enquiry_types">Inquiry Types</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle"><span class="mtext">Blog</span></a>
							<ul class="submenu">
								<li><a href="index?blog_categories">Blog Categories</a></li>
								<li><a href="index?blog">Blog Post's</a></li>
								<li><a href="javascript:;">Blog Comment's</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="mtext">Files</span>
							</a>
							<ul class="submenu child">
								<li><a href="index?view_proposals_files">Proposals Files</a></li>
								<li><a href="index?view_inbox_files">Messages Files</a></li>
								<li><a href="index?view_order_files">Orders Files</a></li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon fa fa-dollar"></span><span class="mtext"> Money </span>
					</a>
					<ul class="submenu">
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon fa fa-plug"></span><span class="mtext">Accounting</span>
							</a>
							<ul class="submenu child">
								<li><a href="index?sales">Sales</a></li>
								<li><a href="index?expenses">Expenses</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon fa fa-plug"></span><span class="mtext">Payouts</span>
							</a>
							<ul class="submenu child">
								<li><a href="index?payouts&status=pending">Pending</a></li>
								<li><a href="index?payouts&status=declined">Declined</a></li>
								<li><a href="index?payouts&status=complete">Completed</a></li>
							</ul>
						</li>
						<li>
							<a href="index?view_coupons">
								Coupon
							</a>
						</li>
						<li>
							<a href="index?view_currencies">
								Currencies
							</a>
						</li>
						<li>
							<a href="index?view_orders">
								<span class="mtext">View Orders</span>
							</a>
						</li>
						<li>
							<a href="index?view_referrals">
								<span class="mtext">View All Referrals</span>
							</a>
						</li>
						<li>
							<a href="index?view_proposal_referrals">
								<span class="mtext">View All Proposal Referrals</span>
							</a>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon fa fa-user-o"></span><span class="mtext">User</span>
					</a>
					<ul class="submenu">
						<li><a href="index?view_sellers">All User</a></li>
						<li><a href="index?view_seller_languages">User Languages</a></li>
						<li><a href="index?view_users">Admins</a></li>
						<li><a href="index?admin_logs">Admin Logiles</a></li>
						<li><a href="index?view_seller_skills">Skills</a></li>
						<li><a href="index?view_seller_levels">Levels</a></li>
					</ul>
				</li>
				<li>
					<a href="index?view_languages" class="dropdown-toggle no-arrow">
						<span class="micon fa fa-language"></span><span class="mtext">Language</span>
					</a>
				</li>
				<li>
					<a href="index?plugins" class="dropdown-toggle no-arrow">
						<span class="micon fa fa-plug"></span><span class="mtext">Plugins</span>
					</a>
				</li>
				<li>
					<a href="index?inbox_conversations" class="dropdown-toggle no-arrow">
						<span class="micon fa fa-eye"></span><span class="mtext">View all Messages</span>
					</a>
				</li>
				<li>
					<a href="index?view_support_requests" class="dropdown-toggle no-arrow">
						<span class="micon fa fa-phone"></span><span class="mtext">Support Center</span>
					</a>
				</li>
				<li>
					<div class="dropdown-divider"></div>
				</li>
				<li>
					<a href="index?credits" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-chat3"></span><span class="mtext">Credits</span>
					</a>
				</li>
				<li>
					<a href="../admin_old/index?dashboard" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-chat3"></span><span class="mtext">Old Admincenter</span>
					</a>
				</li>
				<!-- <div class="dropdown-divider"></div>
			<li>
				<div class="sidebar-small-cap">Extra</div>
			</li>
			<li>
				<a href="javascript:;" class="dropdown-toggle">
					<span class="micon dw dw-edit-2"></span><span class="mtext">Documentation</span>
				</a>
				<ul class="submenu">
					<li><a href="introduction.html">Introduction</a></li>
					<li><a href="getting-started.html">Getting Started</a></li>
					<li><a href="color-settings.html">Color Settings</a></li>
					<li><a href="third-party-plugins.html">Third Party Plugins</a></li>
				</ul>
			</li> -->
			</ul>
		</div>
	</div>
</div>
<?php } ?>