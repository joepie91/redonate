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

try
{
	$sCampaign = Campaign::FindByUrlName($router->uParameters[1]);
}
catch (NotFoundException $e)
{
	http_status_code(404);
	$sPageContents = NewTemplater::Render("404", $locale->strings, array());
	return;
}

$sLogEntry = new LogEntry(0);
$sLogEntry->uType = LogEntry::PAGELOAD;
$sLogEntry->uIp = $_SERVER['REMOTE_ADDR'];
$sLogEntry->uData = json_encode(array());
$sLogEntry->uCampaignId = $sCampaign->sId;
$sLogEntry->uDate = time();
$sLogEntry->uSessionId = session_id();
$sLogEntry->InsertIntoDatabase();

$sPageTitle = "Contribute to {$sCampaign->sName}";
$sPageContents = NewTemplater::Render("landing", $locale->strings, array(
	"can-donate-once" => true, 
	"project-name" => $sCampaign->sName, 
	"urlname" => $sCampaign->sUrlName,
	"error" => $sError
));
