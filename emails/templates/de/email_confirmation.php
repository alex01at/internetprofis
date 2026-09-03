<div align="center" style="vertical-align:top" style="background-color: #fafafa;">
   <table cellpadding="0" cellspacing="0" border="0" width="480" class="table">
      <tbody>
         <tr>
            <td width="100%" align="center" style="vertical-align: top; background-color: #FAFAFA">
               <a href="<?= $site_url; ?>" target="_blank">
                  <img src="<?= $site_logo; ?>" border="0" alt="<?= $site_name; ?>" class="logo">
               </a>
            </td>
         </tr>
         <tr>
            <td width="100%" align="left" style="vertical-align:top">
               <a href="<?= $site_url; ?>" target="_blank">
                  <img src="<?= img_url("banner.jpg"); ?>" class='banner' alt="<?= $site_name; ?>">
               </a>
            </td>
         </tr>
         <tr>
            <td height="30" bgcolor="#ffffff" width="100%"></td>
         </tr>
         <tr>
            <td bgcolor="#ffffff" width="100%" align="center" style="vertical-align:top">
               <table width="390" cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                     <tr>
                        <td width="100%" align="left" style="vertical-align:top">
                           <p class="heading">
                              Willkommen bei <?= $site_name; ?>! Bestätigen Sie Ihre E-Mail-Adresse, um alle Funktionen freizuschalten.
                           </p>
                           <table width="390" cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td width="100%" align="left" style="vertical-align:top" height="50" colspan="3"></td>
                                 </tr>
                                 <tr>
                                    <td width="54" align="left" style="vertical-align:middle">
                                       <img src="<?= img_url("work.jpg"); ?>" alt="Ad-free videos" style="display:block" width="54" border="0">
                                    </td>
                                    <td width="20"></td>
                                    <td align="left" style="vertical-align:middle" width="316">
                                       <p style="font-family:'Roboto',Arial,Helvetica,sans-serif;font-size:21px;font-weight:700;color:#333333;margin:0px!important;padding:0px!important;line-height:20px">
                                          Dienstleistungen beauftragen<br>
                                          <span style="font-size:16px;color:#888888;font-weight:300">Engagieren Sie talentierte Freelancer und starten Sie Ihr Projekt ganz nach Ihren Vorstellungen.</span>
                                       </p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td width="100%" align="left" style="vertical-align:top" height="30" colspan="3"></td>
                                 </tr>
                                 <tr>
                                    <td width="54" align="left" style="vertical-align:middle">
                                       <img src="<?= img_url("freelancer.jpg"); ?>" alt="Save offline" style="display:block" width="54" border="0">
                                    </td>
                                    <td width="20"></td>
                                    <td align="left" style="vertical-align:middle" width="316">
                                       <p style="font-family:'Roboto',Arial,Helvetica,sans-serif;font-size:21px;font-weight:700;color:#333333;margin:0px!important;padding:0px!important;line-height:20px">
                                          Verkaufen starten
                                          <br>
                                          <span style="font-size:16px;color:#888888;font-weight:300">Veröffentlichen Sie Ihre Dienstleistungen auf der Plattform und verdienen Sie Geld.</span>
                                       </p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td width="100%" align="left" style="vertical-align:top" height="30" colspan="3"></td>
                                 </tr>
                                 <tr>
                                    <td width="54" align="left" style="vertical-align:middle">
                                       <img src="<?= img_url("affiliate.jpg"); ?>" alt="Background play" style="display:block" width="54" border="0">
                                    </td>
                                    <td width="20"></td>
                                    <td align="left" style="vertical-align:middle" width="316">
                                       <p style="font-family:'Roboto',Arial,Helvetica,sans-serif;font-size:21px;font-weight:700;color:#333333;margin:0px!important;padding:0px!important;line-height:20px">
                                          Partner werden<br>
                                          <span style="font-size:16px;color:#888888;font-weight:300">Verdienen Sie Geld, indem Sie Dienstleistungen der Plattform weiterempfehlen und bewerben.</span>
                                       </p>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td height="30" bgcolor="#ffffff" width="100%"></td>
         </tr>
         <tr>
            <td bgcolor="#ffffff" width="100%" align="center" style="vertical-align:top">
               <table width="390" cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                     <tr>
                        <td width="100%" align="center" style="vertical-align:top">
                           <a href="<?= $data['verification_link']; ?>" target="_blank">
                              <img src="<?= img_url("confirm.jpg"); ?>" border="0" alt="Jetzt bestätigen" width="180" style="display:block;padding-bottom:20px">
                           </a>
                           <p style="font-family:'Roboto',Arial,Helvetica,sans-serif;font-size:18px;font-weight:400;color:#333333;margin:0px!important;padding:0px!important;line-height:21px">
                              Klicken Sie auf die Schaltfläche, um fortzufahren<br>
                              <font style="color:#8b8b8b;font-size:13px;text-decoration:underline">
                                 <?= $data['verification_link']; ?>
                              </font><br>
                              <font style="color:#8b8b8b;font-size:13px">* Falls die Schaltfläche nicht funktioniert, kopieren Sie den Link und fügen Sie ihn in Ihren Browser ein.</font>
                           </p>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td height="30" bgcolor="#ffffff" width="100%"></td>
         </tr>
         <tr>
            <td width="100%" align="left" style="vertical-align:top" bgcolor="#fafafa">
               <p class="footer-p">© <?= date("Y"); ?> <?= $site_name; ?>. Alle Rechte vorbehalten.<br></p>
            </td>
         </tr>
         <tr>
            <td height="40" bgcolor="#fafafa" width="100%"></td>
         </tr>
      </tbody>
   </table>
</div>