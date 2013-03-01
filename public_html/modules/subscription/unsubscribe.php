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

if(!empty($_POST['submit']))
{
	$sSubscription->uIsActive = false;
	$sSubscription->InsertIntoDatabase();
	
	$sLogEntry = new LogEntry(0);
	$sLogEntry->uType = LogEntry::UNSUBSCRIPTION;
	$sLogEntry->uIp = $_SERVER['REMOTE_ADDR'];
	$sLogEntry->uData = json_encode(array("email" => $sSubscription->sEmailAddress));
	$sLogEntry->uCampaignId = $sCampaign->sId;
	$sLogEntry->uDate = time();
	$sLogEntry->uSessionId = session_id();
	$sLogEntry->InsertIntoDatabase();
	
	flash_notice("We've unsubscribed you.");
	redirect("/manage/{$sSubscription->sEmailAddress}/{$sSubscription->sSettingsKey}");
}

$sPageTitle = "Change pledge amount";
$sPageContents = NewTemplater::Render("subscription/unsubscribe", $locale->strings, array(
	"email"		=> $sSubscription->sEmailAddress,
	"key"		=> $sSubscription->sSettingsKey,
	"name"		=> $sSubscription->sCampaign->sName
));
