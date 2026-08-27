<?php

if($reason == "referral_approved"){
	return $lang['notification']['approved_prposal'];
}
if($reason == "proposal_referral_approved"){
	return $lang['notification']['approved_referral_proposal'];
}

if($reason == "modification"){
	return $lang['notification']['modification'];
	}

if($reason == "declined"){
	return $lang['notification']['declined'];
}

if($reason == "approved"){
	return $lang['notification']['approved'];
}

if($reason == "unapproved_request"){
	return $lang['notification']['unapproved_request'];
}

if($reason == "approved_request"){
	return $lang['notification']['approved_request'];
}

if($reason == "offer"){
	return $lang['notification']['offer'];
}

if($reason == "order"){
	return $lang['notification']['order'];
}

if($reason == "order_tip"){
	return $lang['notification']['order_tip'];
}

if($reason == "order_message"){
	return $lang['notification']['order_message'];
}
//
if($reason == "order_revision"){
	return $lang['notification']['order_revision'];
}

if($reason == "order_completed"){
	return $lang['notification']['order_complete'];
}

if($reason == "order_delivered"){
	return $lang['notification']['order_delivered'];
}

if($reason == "cancellation_request"){
	return $lang['notification']['cancellation_request'];
}

if($reason == "decline_cancellation_request"){
	return $lang['notification']['decline_cancellation_request'];
}

if($reason == "accept_cancellation_request"){
	return $lang['notification']['accept_cancellation_request'];
}

if($reason == "cancelled_by_customer_support"){
	return $lang['notification']['cancelled_by_customer_support'];
}

if($reason == "buyer_order_review"){
	return $lang['notification']['buyer_order_review'];
}
if($reason == "seller_order_review"){
	return $lang['notification']['seller_order_review'];
}

if($reason == "order_cancelled"){
	return $lang['notification']['order_cancelled'];
}

if($reason == "withdrawal_declined"){
	return $lang['notification']['withdrawal_declined'];
}

if($reason == "withdrawal_approved"){
	return $lang['notification']['withdrawal_approved'];
}

if($reason == "extendTimeDeclined"){
	return $lang['notification']['extendTimeDeclined'];
}

if($reason == "extendTimeAccepted"){
	return $lang['notification']['extendTimeAccepted'];
}

if($reason == "buyerExtendTimeAccepted"){
	return $lang['notification']['buyerExtendTimeAccepted'];
}

if($reason == "ticket_reply"){
	return $lang['notification']['ticket_reply'];
}