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
	flash_error("Please enter a valid e-mail address.");
	require("modules/landing.php");
	return;
}

if(empty($_POST['currency']))
{
	flash_error("Please pick a valid currency.");
	require("modules/landing.php");
	return;
}

if(empty($_POST['amount']) || preg_match("([0-9]*[.,][0-9]+|[0-9]+)", $_POST['amount']) == false)
{
	flash_error("Please enter a valid amount.");
	require("modules/landing.php");
	return;
}

try
{
	$exists = false;
	Subscription::CreateFromQuery("SELECT * FROM subscriptions WHERE `EmailAddress` = :EmailAddress AND `Confirmed` = 0",
		array(":EmailAddress" => $_POST['email']));
	$exists = true;
}
catch (NotFoundException $e)
{
	$exists = false;
}

if($exists)
{
	flash_error("That e-mail address has subscribed before and is currently awaiting confirmation.");
	require("modules/landing.php");
	return;
}

try
{
	$exists = false;
	
	foreach(Subscription::FindByEmail($_POST['email']) as $sSubscription)
	{
		if($sSubscription->sCampaignId == $sCampaign->sId && $sSubscription->sIsActive == true)
		{
			$exists = true;
			$sExistingSubscription = $sSubscription;
		}
	}
	
}
catch (NotFoundException $e)
{
	$exists = false;
}

if($exists)
{
	$sPageContents = NewTemplater::Render("subscription/change", $locale->strings, array());
	
	$sChangeRequest = new ChangeRequest(0);
	$sChangeRequest->uKey = random_string(16);
	$sChangeRequest->uOldCurrency = $sExistingSubscription->sCurrency;
	$sChangeRequest->uOldAmount = $sExistingSubscription->sAmount;
	$sChangeRequest->uNewCurrency = $_POST['currency'];
	$sChangeRequest->uNewAmount = str_replace(",", ".", $_POST['amount']);
	$sChangeRequest->uSubscriptionId = $sExistingSubscription->sId;
	$sChangeRequest->uCampaignId = $sExistingSubscription->sCampaign->sId;
	$sChangeRequest->uIsConfirmed = false;
	$sChangeRequest->uDate = time();
	$sChangeRequest->InsertIntoDatabase();
	
	$sEmail = $sChangeRequest->GenerateEmail();
	
	send_mail($sExistingSubscription->sEmailAddress, "Changes to your pledge to {$sExistingSubscription->sCampaign->sName}", $sEmail['text'], $sEmail['html']);
	
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
$sSubscription->uIsConfirmed = false;
$sSubscription->uIsActive = false;
$sSubscription->uCampaignId = $sCampaign->sId;
$sSubscription->InsertIntoDatabase();

send_mail($_POST['email'], "Please confirm your ReDonate pledge.", 
	NewTemplater::Render("email/confirm.txt", $locale->strings, array(
		"project-name" => $sCampaign->sName, 
		"confirmation-url" => "http://redonate.net/confirm/{$sSubscription->sEmailAddress}/{$sSubscription->sConfirmationKey}/",
		"amount" => Currency::Format($sSubscription->sCurrency, $sSubscription->sAmount))),
	NewTemplater::Render("email/layout.html", $locale->strings, array(
		"contents" => NewTemplater::Render("email/confirm.html", $locale->strings, array(
			"project-name" => $sCampaign->sName, 
			"confirmation-url" => "http://redonate.net/confirm/{$sSubscription->sEmailAddress}/{$sSubscription->sConfirmationKey}/",
			"amount" => Currency::Format($sSubscription->sCurrency, $sSubscription->sAmount)))
	))
);

$sPageContents = NewTemplater::Render("subscription/success", $locale->strings, array());
$sPageTitle = "Thanks for your pledge!";
