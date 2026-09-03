
<div class="box" align="center">
  <div class="container" style="max-width: 632px;margin: 0 auto;">
    <div class="row bg-white o_sans">

      <div class="icon-container">
        <div class="icon bg-green" align="center" style="background-color: <?= $site_color;?>;">
          <img src="<?= img_url("check-white.png"); ?>" width="48" height="48">
        </div>
      </div>

      <h2 class="o_heading">Hallo <?= $data['user_name']; ?></h2>

      <p class="text-left text-muted" style="margin-bottom: 5px; margin-top: 15px;">
        Wir haben Ihr Ticket soeben geschlossen. Sollte Ihr Anliegen noch nicht gelöst sein, kontaktieren Sie uns bitte, indem Sie auf diese E-Mail antworten oder unseren Kundensupport auf der Website nutzen.
      </p>

      <p class="text-left text-muted" style="margin-bottom: 5px;">Mit freundlichen Grüßen,</p>

      <p class="text-left text-muted" style="margin-bottom: 0px;">Ihr <?= $site_name; ?>-Team.</p>

    </div>
  </div>
</div>