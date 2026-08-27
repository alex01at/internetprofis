<?php 
$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$enable_maintenance_mode = $row_general_settings->enable_maintenance_mode;

?>
<div class="header-left">
<div class="menu-icon fa fa-bars"></div>
			<div class="col"><span class="lang">current Settings for: </div>
			<div class="col">
				<select id="languageSelect">
				<?php
				$get_languages = $db->select("languages");
				while($row_languages = $get_languages->fetch()){
				$id = $row_languages->id;
				$title = $row_languages->title;
				?>
				<option data-url="<?= "$site_url/admin/index?change_language=$id"; ?>" <?= ($id == $_SESSION["adminLanguage"])?"selected":""; ?>>
				<?= $title; ?>
				</option>
		    	<?php } ?>
			</select>
		</div>
		</div>
		<div class="header-right">
		<?php if($enable_maintenance_mode == "yes"){ ?>
		<div class="dashboard-setting user-notification">
				<a class="dropdown-toggle no-arrow" href="index?general"  title="Maintenance Mode active">
						<i class="icon-copy fa fa-exclamation-triangle"></i>
					</a></div>
					<?php } ?>


			<div class="dashboard-setting user-notification">
				<a class="dropdown-toggle no-arrow" href="<?= $site_url; ?>" target="_blank" title="go to website">
						<i class="icon-copy fa fa-eye"></i>
					</a></div>
			
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
                        <?php if(!empty($admin_image)){ ?>
         <img src="<?= getImageUrl("admins",$admin_image); ?>" width="50px" height="50px" class="rounded-circle text-white">
     <?php }else{ ?>
         <img src="admin_images/empty-image.png" width="30" height="30" class="rounded-circle text-white">
     <?php } ?>
						</span>
						<span class="user-name"><?= $admin_name; ?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="index?user_profile=<?= $admin_id; ?>"><i class="dw dw-user1"></i> Profile & Settings</a>
						<a class="dropdown-item" href="faq.html"><i class="dw dw-help"></i> Help</a>
						<a class="dropdown-item" href="logout"><i class="dw dw-logout"></i> Log Out</a>
					</div>
				</div>
			</div>
			
		</div>
		<script>
	$(document).ready(function(){
		$("#languageSelect").change(function(){
			var url = $("#languageSelect option:selected").data("url");
			window.location.href = url;
		});
	});
	</script>