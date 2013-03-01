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

if(!isset($_APP)) { die("Unauthorized."); }

$sPaymentRequest->uPaid = true;
$sPaymentRequest->InsertIntoDatabase();

$sLogEntry = new LogEntry(0);
$sLogEntry->uType = LogEntry::DONATION_SKIPPED;
$sLogEntry->uIp = $_SERVER['REMOTE_ADDR'];
$sLogEntry->uData = json_encode(array("payment_request" => $sPaymentRequest->sId));
$sLogEntry->uCampaignId = $sPaymentRequest->sCampaign->sId;
$sLogEntry->uDate = time();
$sLogEntry->uSessionId = session_id();
$sLogEntry->InsertIntoDatabase();

$sPageTitle = "Thanks for letting us know.";
$sPageContents = NewTemplater::Render("payment/skipped", $locale->strings, array());
