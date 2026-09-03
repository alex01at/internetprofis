<div id="payment-modal-1" class="modal fade" style="overflow-y:scroll;z-index:5051;"><!--- payment-modal Starts --->
   <div class="modal-dialog"><!--- modal-dialog Starts --->

      <div class="modal-content"><!--- modal-content Starts --->

         <div class="modal-header"><!-- modal-header Starts -->
            
            <h5 class="modal-title"> 
               <span class="float-left"><?= $lang['dusupay']['pay_with_dusupay']; ?></span>
            </h5>

            <button class="closeExtendTimePayment close" data-dismiss="modal">
               <span>&times;</span>
            </button>

         </div><!-- modal-header Ends -->

         <div class="modal-body"><!--- modal-body Starts --->
         
            <form method="post" action="" id="dusupay-1"><!--- form Starts --->
               
               <input type="hidden" name="action" value="<?= $form_action; ?>">

               <div class="form-group"><!--- form-group Starts --->
                  <label><?= $lang['dusupay']['select_country']; ?> </label>
                  <select name="country" class="form-control" required="">
                     <option value="UG"><?= $lang['dusupay']['countries']['UG']; ?></option>
                     <option value="KE"><?= $lang['dusupay']['countries']['KE']; ?></option>
                     <option value="RW"><?= $lang['dusupay']['countries']['RW']; ?></option>
                     <option value="BI"><?= $lang['dusupay']['countries']['BI']; ?></option>
                     <option value="GH"><?= $lang['dusupay']['countries']['GH']; ?></option>
                     <option value="CM"><?= $lang['dusupay']['countries']['CM']; ?></option>
                     <option value="ZA"><?= $lang['dusupay']['countries']['ZA']; ?></option>
                     <option value="NG"><?= $lang['dusupay']['countries']['NG']; ?></option>
                     <option value="ZM"><?= $lang['dusupay']['countries']['ZM']; ?></option>
                     <option value="CI"><?= $lang['dusupay']['countries']['CI']; ?></option>
                     <option value="SN"><?= $lang['dusupay']['countries']['SN']; ?></option>
                     <option value="TZ"><?= $lang['dusupay']['countries']['TZ']; ?></option>
                     <option value="US"><?= $lang['dusupay']['countries']['US']; ?></option>
                     <option value="GB"><?= $lang['dusupay']['countries']['GB']; ?></option>
                     <option value="EU"><?= $lang['dusupay']['countries']['EU']; ?></option>
                  </select>
               </div><!--- form-group Ends --->

               <div class="form-group"><!--- form-group Starts --->
                  <label> <?= $lang['dusupay']['payment_method']; ?> </label>
                  <select name="method" class="form-control" required="">
                     <option value="MOBILE_MONEY"> Mobile Money </option>
                     <option value="CARD"> <?= $lang['dusupay']['card']; ?> </option>
                     <option value="BANK"> <?= $lang['dusupay']['bank']; ?> </option>
                     <option value="CRYPTO"><?= $lang['dusupay']['crypto']; ?></option>
                  </select>
               </div><!--- form-group Ends --->

               <hr>

               <div class="form-group mb-0 text-center"><!--- form-group Starts --->

                  <button class="btn btn-success" type="submit"><?= $lang['continue']; ?></button>

                  <!-- <input type="submit" name="dusupay" value="Contine" class="btn btn-success" /> -->
                  <!-- <button type="submit" id="contine" data-toggle="modal" data-dismiss="modal" data-target="#payment-modal-2" class="btn btn-success">Contine</button> -->

               </div><!--- form-group Ends --->

            </form><!--- form Ends --->

         </div><!--- modal-body Ends --->
      
      </div><!--- modal-content Ends --->
   </div><!--- modal-dialog Ends --->
</div><!--- payment-modal Ends --->


<div id="payment-modal-2" class="modal fade" style="overflow-y:scroll;z-index:5051;"><!--- payment-modal Starts ---->
   <div class="modal-dialog"><!--- modal-dialog Starts --->

   </div><!--- modal-dialog Ends --->
</div><!--- payment-modal Ends --->

<script>

$(document).ready(function(){
   
   $("#dusupay-1").submit(function(event){

      $("#wait").addClass("loader");
      event.preventDefault();

      var country = $("#dusupay-1 select[name='country']").val();
      var method = $("#dusupay-1 select[name='method']").val();

      $.ajax({
      
         method: "POST",
         url: "<?= $site_url; ?>/includes/comp/check_provider_ids",
         data: $('#dusupay-1').serialize()

      }).done(function(data){

         console.log(data);

         if(data == "success"){

            $.ajax({
               method: "POST",   
               url: "<?= $site_url; ?>/includes/comp/dusupay_payment_modal_2",
               data: $('#dusupay-1').serialize()
            }).done(function(data){
               $("#wait").removeClass("loader");
               $("#payment-modal-1").modal('hide');
               $("#payment-modal-2").modal('show');
               $("#payment-modal-2 .modal-dialog").html(data);
            });

         }else{
            $("#wait").removeClass("loader");

            if(data == "There are no options found for "+method+" collections in "+country){
               alert(<?= json_encode($lang['dusupay']['country_not_supported']); ?>)
            }else{
               alert(data);
            }

            console.log(data);

         }

      });

   });

});

</script>