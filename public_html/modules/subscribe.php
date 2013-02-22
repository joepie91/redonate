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
	$sPageContents = NewTemplater::Render("404", $locale->strings, array());
	return;
}

if(empty($_POST['email']) || User::CheckIfEmailValid($_POST['email']) == false)
{
	$sError = "Please enter a valid e-mail address.";
	require("modules/landing.php");
	return;
}

if(empty($_POST['currency']))
{
	$sError = "Please pick a valid currency.";
	require("modules/landing.php");
	return;
}

if(empty($_POST['amount']) || preg_match("([0-9]*[.,][0-9]+|[0-9]+)", $_POST['amount']) == false)
{
	$sError = "Please enter a valid amount.";
	require("modules/landing.php");
	return;
}

try
{
	Subscription::FindByEmail($_POST['email']);
	$exists = true;
}
catch (NotFoundException $e)
{
	$exists = false;
}

if($exists)
{
	$sPageContents = NewTemplater::Render("subscription/change", $locale->strings, array());
	/* TODO: Change request */
	return;
}

$sLogEntry = new LogEntry(0);
$sLogEntry->uType = LogEntry::SUBSCRIPTION;
$sLogEntry->uIp = $_SERVER['REMOTE_ADDR'];
$sLogEntry->uData = json_encode(array("email" => $_POST['email']));
$sLogEntry->uCampaignId = $sCampaign->sId;
$sLogEntry->uDate = time();
$sLogEntry->uSessionId = session_id();
$sLogEntry->InsertIntoDatabase();

$sSubscription = new Subscription(0);
$sSubscription->uEmailAddress = $_POST['email'];
$sSubscription->uConfirmationKey = random_string(25);
$sSubscription->uSettingsKey = random_string(25);
$sSubscription->uCurrency = $_POST['currency'];
$sSubscription->uAmount = str_replace(",", ".", $_POST['amount']);
$sSubscription->uSubscriptionDate = time();
$sSubscription->uConfirmed = False;
$sSubscription->uCampaignId = $sCampaign->sId;
$sSubscription->InsertIntoDatabase();

send_mail($_POST['email'], "Please confirm your ReDonate pledge.", 
	NewTemplater::Render("email/confirm.txt", $locale->strings, array(
		"project-name" => $sCampaign->sName, 
		"confirmation-url" => "http://redonate.cryto.net/confirm/{$sSubscription->sEmailAddress}/{$sSubscription->sConfirmationKey}/",
		"amount" => "$5.00")),
	NewTemplater::Render("email/layout.html", $locale->strings, array(
		"contents" => NewTemplater::Render("email/confirm.html", $locale->strings, array(
			"project-name" => $sCampaign->sName, 
			"confirmation-url" => "http://redonate.cryto.net/confirm/{$sSubscription->sEmailAddress}/{$sSubscription->sConfirmationKey}/",
			"amount" => "$5.00"))
	))
);

$sPageContents = NewTemplater::Render("subscription/success", $locale->strings, array());
$sPageTitle = "Thanks for your pledge!";
