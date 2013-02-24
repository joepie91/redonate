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

$sCampaigns = array();

$sPercentages = array();
$sTotals = array();
$sProjections = array();
$sSubscribers = array();

try
{
	foreach(Campaign::CreateFromQuery("SELECT * FROM campaigns WHERE `OwnerId` = :UserId", array(":UserId" => $sCurrentUser->sId)) as $sCampaign)
	{
		$sCampaign->UpdateStatistics();
		$sPaymentMethods = array();
		
		try
		{
			foreach(PaymentMethod::CreateFromQuery("SELECT * FROM payment_methods WHERE `CampaignId` = :CampaignId", 
				array(":CampaignId" => $sCampaign->sId)) as $sPaymentMethod)
			{
				$sPaymentMethods[] = $sPaymentMethod->GetLogo();
			}
		}
		catch (NotFoundException $e)
		{
			/* No payment methods...? */
		}
		
		if($sCampaign->sHaveData)
		{
			$sPercentages[] = $sCampaign->sDonationRate;
			$sTotals[] = $sCampaign->sMonthlyTotal;
			$sProjections[] = $sCampaign->sMonthlyProjection;
		}
		
		$sSubscribers[] = $sCampaign->sSubscriberCount;
		
		$sCampaigns[] = array(
			"name" => $sCampaign->sName,
			"subscribers" => number_format($sCampaign->sSubscriberCount, 0),
			"rate" => number_format($sCampaign->sDonationRate, 2),
			"total" => Currency::Format("usd", $sCampaign->sMonthlyTotal),
			"projection" => Currency::Format("usd", $sCampaign->sMonthlyProjection),
			"one-off" => $sCampaign->sAllowOneTime,
			"payment-methods" => $sPaymentMethods,
			"have-data" => $sCampaign->sHaveData
		);
	}
}
catch (NotFoundException $e)
{
	/* pass */
}

$sPercentages = (empty($sPercentages)) ? array(0) : $sPercentages;
$sTotals = (empty($sTotals)) ? array(0) : $sTotals;
$sProjections = (empty($sProjections)) ? array(0) : $sProjections;
$sSubscribers = (empty($sSubscribers)) ? array(0) : $sSubscribers;

$sPageTitle = "Dashboard";
$sPageContents = NewTemplater::Render("dashboard", $locale->strings, array(
	"campaigns" => $sCampaigns,
	"total-rate" => number_format(array_sum($sPercentages) / count($sPercentages), 2),
	"total-subscribers" => number_format(array_sum($sSubscribers), 0),
	"total-total" => Currency::Format("usd", array_sum($sTotals)),
	"total-projection" => Currency::Format("usd", array_sum($sProjections))
));
