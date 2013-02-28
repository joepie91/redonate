<?php
/*
 * ReDonate is more free software. It is licensed under the WTFPL, which
 * allows you to do pretty much anything with it, without having to
 * ask permission. Commercial use is allowed, and no attribution is
 * required. We do politely request that you share your modifications
 * to benefit other developers, but you are under no enforced
 * obligation to do so :)
 * 
 * Please read the accompanying LICENSE document for the full WTFPL
 * licensing text.
 */

$_APP = true;
require("includes/base.php");

if(php_sapi_name() !== "cli")
{
	//http_status_code(403);
	//die();
}

/* This cronjob will send out donation reminder e-mails for every user
 * that hasn't received an e-mail in the past month. It will also 
 * re-generate statistics for every campaign, and store them in the
 * historical statistics logs.
 */

/* First, we'll start out sending reminder e-mails. */

try
{
	$sSubscriptions = Subscription::CreateFromQuery("SELECT * FROM subscriptions WHERE `Confirmed` = 1 AND `LastEmail` IS NULL OR `LastEmail` < DATE_SUB(NOW(), INTERVAL 1 MONTH)");
}
catch (NotFoundException $e)
{
	$sSubscriptions = array();
}

foreach($sSubscriptions as $sSubscription)
{
	/* Create a payment request */
	$sPaymentRequest = new PaymentRequest(0);
	$sPaymentRequest->uCurrency = $sSubscription->uCurrency;
	$sPaymentRequest->uAmount = $sSubscription->uAmount;
	$sPaymentRequest->uCampaignId = $sSubscription->uCampaignId;
	$sPaymentRequest->uSubscriptionId = $sSubscription->sId;
	$sPaymentRequest->uKey = random_string(16);
	$sPaymentRequest->uPaid = false;
	$sPaymentRequest->uDate = time();
	$sPaymentRequest->InsertIntoDatabase();
	
	/* Log an event */
	$sLogEntry = new LogEntry(0);
	$sLogEntry->uType = LogEntry::DONATION_ASKED;
	$sLogEntry->uIp = $_SERVER['REMOTE_ADDR'];
	$sLogEntry->uData = json_encode(array("payment_request" => $sPaymentRequest->sId));
	$sLogEntry->uCampaignId = $sPaymentRequest->sCampaign->sId;
	$sLogEntry->uDate = time();
	$sLogEntry->uSessionId = session_id();
	$sLogEntry->InsertIntoDatabase();
	
	/* Send an e-mail */
	$sEmail = $sPaymentRequest->GenerateEmail();
	send_mail($sSubscription->sEmailAddress, "Your monthly donation to {$sSubscription->sCampaign->sName}", $sEmail['text'], $sEmail['html']);
	
	/* Update the subscription to reflect the last sent e-mail */
	$sSubscription->uLastEmail = time();
	$sSubscription->InsertIntoDatabase();
}
