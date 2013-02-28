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
	$sCampaign = Campaign::CreateFromQuery("SELECT * FROM campaigns WHERE `UrlName` = :UrlName", array(":UrlName" => $router->uParameters[1]), 30, true);

}
catch (NotFoundException $e)
{
	throw new RouterException("Campaign does not exist.");
}

if($sCampaign->VerifyAdministratorAccess($_SESSION['user_id']) === false)
{
	throw new RouterException("Not authorized to administrate this campaign.");
}

$sPaymentMethods = array();

try
{
	foreach(PaymentMethod::CreateFromQuery("SELECT * FROM payment_methods WHERE `CampaignId` = :CampaignId", 
		array(":CampaignId" => $sCampaign->sId)) as $sPaymentMethod)
	{
		$sNewMethod = $sPaymentMethod->GetLogo();
		$sNewMethod['address'] = $sPaymentMethod->sAddress;
		$sNewMethod['id'] = $sPaymentMethod->sId;
		$sPaymentMethods[] = $sNewMethod;
	}
}
catch (NotFoundException $e)
{
	/* No payment methods...? */
}

$sEventTotal = $sCampaign->sPastMonthSubscriptions + $sCampaign->sPastMonthUnsubscriptions + $sCampaign->sPastMonthDonations + $sCampaign->sPastMonthNonDonations;

if($sEventTotal !== 0)
{
	$sSubscriptionPercentage = ($sCampaign->sPastMonthSubscriptions / $sEventTotal) * 100;
	$sUnsubscriptionPercentage = ($sCampaign->sPastMonthUnsubscriptions / $sEventTotal) * 100;
	$sDonationPercentage = ($sCampaign->sPastMonthDonations / $sEventTotal) * 100;
	$sNonDonationPercentage = ($sCampaign->sPastMonthNonDonations / $sEventTotal) * 100;
	$sStatisticsAvailable = true;
}
else
{
	/* We obviously can't divide by zero - and nothing happened anyway. */
	$sSubscriptionPercentage = 0;
	$sUnsubscriptionPercentage = 0;
	$sDonationPercentage = 0;
	$sNonDonationPercentage = 0;
	$sStatisticsAvailable = false;
}

$sPageTitle = "Dashboard for {$sCampaign->sName}";
$sPageContents = NewTemplater::Render("campaign/dashboard", $locale->strings, array(
	"name"				=> $sCampaign->sName,
	"urlname"			=> $sCampaign->sUrlName,
	"payment-methods"		=> $sPaymentMethods,
	"subscriptions-amount"		=> $sCampaign->sPastMonthSubscriptions,
	"subscriptions-percentage"	=> $sSubscriptionPercentage,
	"unsubscriptions-amount"	=> $sCampaign->sPastMonthUnsubscriptions,
	"unsubscriptions-percentage"	=> $sUnsubscriptionPercentage,
	"donations-amount"		=> $sCampaign->sPastMonthDonations,
	"donations-percentage"		=> $sDonationPercentage,
	"nondonations-amount"		=> $sCampaign->sPastMonthNonDonations,
	"nondonations-percentage"	=> $sNonDonationPercentage,
	"statistics-available"		=> $sStatisticsAvailable
));

