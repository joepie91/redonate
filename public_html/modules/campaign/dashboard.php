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

$sPageTitle = "Dashboard for {$sCampaign->sName}";
$sPageContents = NewTemplater::Render("campaign/dashboard", $locale->strings, array(
	"name"			=> $sCampaign->sName,
	"urlname"		=> $sCampaign->sUrlName,
	"payment-methods"	=> $sPaymentMethods
));

