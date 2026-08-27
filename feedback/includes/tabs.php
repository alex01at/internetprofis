<div class="card">
	<div class="card-body <?= $textRight; ?>">

		<form action="post-idea" method="post"><!--- form Starts --->
		  
			<div class="form-group"><!--- form-group Starts --->

			<h5> <?=$lang['idea_suggest'];?> ...</h5>

			<div class="input-group mb-3">

			<input type="text" name="title" class="form-control form-control-lg" placeholder="<?= $lang['placeholder']['idea'];?>" required/>

			<div class="input-group-addon bg-success">
				<button class="btn bg-transparent text-white rounded-right" name="post_idea" type="submit"><?=$lang['post_new_idea'] ;?></button>
			</div>

			</div>

			</div><!--- form-group Ends --->

		</form><!--- form Ends --->

		<?php if(isset($_SESSION["seller_user_name"])){ ?>

		<ul class="nav nav-tabs">
		  <li class="nav-item">
		    <a class="nav-link <?php if(!$input->get('comments')){ echo "active"; } ?>" data-toggle="tab" href="#ideas"><?=$lang['ideas'];?></a>
		  </li>
		  <li class="nav-item">
		   <a class="nav-link <?php if($input->get('comments')){ echo "active"; } ?>" data-toggle="tab" href="#comments"><?=$lang['comments'];?></a>
		  </li>
		</ul>

		<div class="tab-content">
		  <div id="ideas" class="tab-pane fade <?php if(!$input->get('comments')){ echo "show active"; } ?> mt-4">
				<?php 
					
					$ideas = $db->select("ideas",["seller_id"=>$login_seller_id],"DESC");
					while($idea = $ideas->fetch()){ 
					$count_comments = $db->count("comments",array("idea_id" => $idea->id));
				?>
				<div class="card mb-3">
				<div class="card-body">
				<h5 class="mb-3">
				<a href="idea?id=<?= $idea->id; ?>">
				<?= $idea->title; ?>
				</a>
				<div class="btn-group btn-group-sm <?=($lang_dir=="right"?'float-left':'float-right')?>">
				<a href="post-idea?id=<?= $idea->id; ?>" class="btn btn-primary">
					<i class="fas fa-pencil-alt"></i> <?=$lang['button']['edit'];?>
				</a>
				<a href="index?delete_idea=<?= $idea->id; ?>/" class="btn btn-danger">
					<i class="fas fa-trash-alt"></i> <?=$lang['th']['delete'];?>
				</a>
				</div>
				</h5>
				<p class="mb-2"><?= substr($idea->content, 0,176); ?></p>
				<span class="text-muted"><?= $count_comments; ?> <?=$lang['comments'];?></span>
				</div>
				</div>
				<?php } ?>
				<?php if($ideas->rowCount() == 0){  ?>
					<h3 class="text-center"><?=$lang['no_idea'];?></h3>
				<?php } ?>
		  </div>
		  <div id="comments" class="tab-pane <?php if($input->get('comments')){ echo "show active"; } ?> fade">
		  	<ul class="list-unstyled mt-4 text-left">
		  		<?php 
		  			$comments = $db->select("comments",["seller_id"=>$login_seller_id],"DESC");
		  			while($comment = $comments->fetch()){ 
						$idea = $db->select("ideas",["id"=>$comment->idea_id])->fetch()->title;
						$seller = $db->select("sellers",["seller_id"=>$comment->seller_id])->fetch();
		  		?>
				<li class="media mb-3">

				<img class="mr-3 img-thumbnail" src="<?= getImageUrl2("sellers","seller_image",$seller->seller_image); ?>" width="50">

				<div class="media-body">
				<h5 class="mt-0 mb-1">
				<?= $seller->seller_user_name; ?>
				<small><?=$lang['comment_on'];?> - <?= $idea; ?></small>
				<a href="index?delete_comment=<?= $comment->id; ?>" class="btn btn-danger btn-sm float-right">
					<i class="fas fa-trash-alt"></i> <?=$lang['th']['delete'];?>
				</a>
				</h5>
				<p><?= $comment->comment; ?></p>
				</div>

				</li>
			<?php } ?>
			<?php if($comments->rowCount() == 0){  ?>
				<h3 class="text-center"><?=$lang['no_comments'];?></h3>
			<?php } ?>
			</ul>
		  </div>
		</div>

		<?php }else{ ?>
		<div class="alert alert-info">
			<p class="lead mb-0 font-weight-normal"><?=$lang['login_for_idea'];?></p>
		</div>
		<?php } ?>

	</div>
</div>