<div class="card mb-3"><!--- card Starts --->

   <div class="card-body <?= $textRight; ?>"><!--- card-body Starts --->

      <h4 class="mb-3"><?= $count_comments; ?> <?= $lang['blog']['comments']; ?></h4>

      <?php if(isset($_SESSION["seller_user_name"])){ ?>

      <form action="" method="post">

        <div class="form-group"><!--- form-group Starts --->
         <textarea name="comment" class="form-control <?= $textRight; ?>" placeholder="<?= $lang['blog']['add_comment']; ?>"></textarea>
        </div><!--- form-group Ends --->

        <div class="form-group"><!--- form-group Starts --->
         <button class="btn btn-success" name="submit" type="submit"> <?= $lang['blog']['post_comment']; ?> </button>
        </div><!--- form-group Ends --->

      </form>

      <?php }else{ ?>

      <div class="alert alert-info rounded-0">
          
      <p class="mt-1 mb-1 text-center">
         <strong><?= $lang['sorry']; ?></strong> <?= $lang['blog']['login_to_comment']; ?> <?= $site_email_address; ?>
      </p>

      </div>

      <?php } ?>

      <ul class="list-unstyled mt-4 text-left">

      <?php 

      while($comment = $comments->fetch()){ 
      $seller = $db->select("sellers",["seller_id"=>$comment->seller_id])->fetch();

      ?>

        <li class="media mb-3">
          <img class="mr-3 img-thumbnail" src="<?= getImageUrl2("sellers","seller_image",$seller->seller_image); ?>" width="50">
          <div class="media-body">
            <h5 class="mt-0 mb-1">
             <?= $seller->seller_user_name; ?>
             <small>
              <?= $lang['blog']['commented_on']; ?> <?= $comment->date; ?>
              <?php if($comment->seller_id == @$login_seller_id){ ?>
              <a href="index?delete_comment=<?= $comment->id; ?>&post_id=<?= $id; ?>" class="btn btn-sm btn-success"style="font-size: 13px;">
                <i class="fa fa-trash-o"></i> <?= $lang['button']['delete']; ?>
              </a>
              <?php } ?>
            </small>
            </h5>
            <?= htmlspecialchars($comment->comment); ?>
          </div>
        </li>

      <?php } ?>

      </ul>

   </div><!--- card-body Ends --->
</div><!--- card Ends --->

<?php

   if(isset($_POST['submit'])){

     $data = array(
        "post_id" => $id,
        "seller_id" => $login_seller_id,
        "comment" => $input->post('comment'),
        "date" => date("F m, Y")
      );

      if($db->insert("post_comments", $data)){
        redirect("$id/$url");
      }

   }

?>