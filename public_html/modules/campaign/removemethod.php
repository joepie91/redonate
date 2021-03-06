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

try
{
	$sPaymentMethod = new PaymentMethod($router->uParameters[2]);
}
catch (NotFoundException $e)
{
	throw new RouterException("Payment method does not exist.");
}

if($sPaymentMethod->sCampaignId !== $sCampaign->sId)
{
	throw new RouterException("Payment method does not belong to campaign.");
}

/* TODO: Implement object deletion in CPHP */
$database->CachedQuery("DELETE FROM payment_methods WHERE `Id` = :Id", array(":Id" => $sPaymentMethod->sId));

flash_notice("The payment method was successfully removed.");
redirect("/dashboard/{$sCampaign->sUrlName}");

