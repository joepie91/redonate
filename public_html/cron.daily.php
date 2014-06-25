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
	http_status_code(403);
	die();
}

/* This cronjob will send out donation reminder e-mails for every user
 * that hasn't received an e-mail in the past month. It will also 
 * re-generate statistics for every campaign, and store them in the
 * historical statistics logs.
 */

/* First, we will update the exchange rates. */

Currency::UpdateRates();

/* Then, we'll start out sending reminder e-mails. */

try
{
	$sSubscriptions = Subscription::CreateFromQuery("SELECT * FROM subscriptions WHERE `Confirmed` = 1 AND `Active` = 1 AND (`LastEmail` IS NULL OR `LastEmail` < DATE_SUB(NOW(), INTERVAL 1 MONTH))");
}
catch (NotFoundException $e)
{
	$sSubscriptions = array();
}

foreach($sSubscriptions as $sSubscription)
{
	$sSubscription->SendPaymentRequest();
}

/* Now, we'll log a historical statistics snapshot for every campaign. */

try
{
	$sCampaigns = Campaign::CreateFromQuery("SELECT * FROM campaigns");
	$found = true;
}
catch (NotFoundException $e)
{
	/* No campaigns are in the database yet. */
	$found = false;
}

if($found)
{
	foreach($sCampaigns as $sCampaign)
	{
		$sCampaign->UpdateStatistics();
		$sStatisticsEntry = $sCampaign->CreateStatisticsEntry();
		$sStatisticsEntry->InsertIntoDatabase();
	}
}
