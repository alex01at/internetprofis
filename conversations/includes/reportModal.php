	<div id="report-modal" class="modal fade"><!-- report-modal modal fade Starts -->
	<div class="modal-dialog"><!-- modal-dialog Starts -->
	<div class="modal-content"><!-- modal-content Starts -->
	<div class="modal-header p-2 pl-3 pr-3"><!-- modal-header Starts -->
	<?= $lang['report_modal']['report_message']; ?>
	<button class="close" data-dismiss="modal">
	<span> &times; </span>
	</button>
	</div><!-- modal-header Ends -->
	<div class="modal-body"><!-- modal-body p-0 Starts -->

	<h6><?= $lang['report_modal']['why_report_message']; ?></h6>

	<form action="" method="post">

	<input type="hidden" name="message_group_id" value="<?= $message_group_id; ?>">

	<div class="form-group mt-3"><!--- form-group Starts --->

	<select class="form-control float-right" name="reason" required="">
	<option value=""><?= $lang['report_modal']['select_reason']; ?></option>
	<option><?= $lang['report_modal']['asked_for_payment_outside']; ?><?= $site_name; ?><?= $lang['report_modal']['asked_for_payment_outside_suffix']; ?>.</option>
	<option><?= $lang['report_modal']['behaved_inappropriately']; ?></option>
	<option><?= $lang['report_modal']['sent_spam']; ?></option>
	<option><?= ucfirst($lang['other']); ?></option>
	</select>

	</div><!--- form-group Ends --->

	<br>
	<br>

	<div class="form-group mt-1 mb-3"><!--- form-group Starts --->
	<label> <?= $lang['additional_information']; ?> </label>
	<textarea name="additional_information" rows="3" class="form-control" required=""></textarea>
	</div><!--- form-group Ends --->
	<button type="submit" name="submit_report" class="float-right btn btn-sm btn-success">
	<?= $lang['report_modal']['submit_report']; ?>
	</button>
	</form>
	
	</div><!-- modal-body p-0 Ends -->
	</div><!-- modal-content Ends -->
	</div><!-- modal-dialog Ends -->
	</div><!-- report-modal modal fade Ends -->