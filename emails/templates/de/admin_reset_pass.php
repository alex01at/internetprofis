<div class="box" align="center">
  <div class="container" style="max-width: 632px;margin: 0 auto;">
    <div class="row o_sans" style="background-color: <?= $site_color;?>">
      
      <div class="icon-container">
        <div class="icon bg-white" align="center" style="">
          <img src="<?= img_url("key.png"); ?>" width="48" height="48">
        </div>
      </div>

      <h2 class="o_heading o_mb-xxs">Passwort zurücksetzen</h2>

      <p class="o_mb-md">Sie haben das Zurücksetzen Ihres Passworts angefordert. Klicken Sie unten, um ein neues Passwort zu vergeben.</p>

      <div class="btn btn-white o_heading o_text" >
        <a class="o_text-primary" style="color: <?= $site_color;?>" href='<?= $site_url; ?>/admin/change_password?code=<?= $data['admin_pass']; ?>'>
          Passwort ändern
        </a>
      </div>

    </div>
  </div>
</div>