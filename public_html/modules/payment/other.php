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
	$sPaymentMethod = PaymentMethod::CreateFromQuery("SELECT * FROM payment_methods WHERE `CampaignId` = :CampaignId AND `Id` = :Id", 
		array(":CampaignId" => $sPaymentRequest->sCampaign->sId, ":Id" => $router->uParameters[4]), 0, true);
}
catch (NotFoundException $e)
{
	throw new RouterException("No such payment method found.");
}

$sPageContents = NewTemplater::Render("payment/other", $locale->strings, array(
	"name"		=> $sPaymentMethod->sCustomName,
	"address"	=> $sPaymentMethod->sAddress,
	"amount"	=> Currency::Format($sPaymentRequest->sCurrency, $sPaymentRequest->sAmount),
	"done-url"	=> "/pay/{$sPaymentRequest->sSubscription->sEmailAddress}/{$sPaymentRequest->sId}/{$sPaymentRequest->sKey}/{$sPaymentMethod->sId}/done"
));
