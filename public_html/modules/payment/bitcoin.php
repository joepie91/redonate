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
	$sPaymentMethod = $sPaymentRequest->sCampaign->GetPaymentMethod(PaymentMethod::BITCOIN);
}
catch (NotFoundException $e)
{
	throw new RouterException("No such payment method found.");
}

if($sPaymentRequest->sCurrency != "btc")
{
	$sAmount = Currency::Convert("btc", $sPaymentRequest->sCurrency, $sPaymentRequest->sAmount);
}
else
{
	$sAmount = $sPaymentRequest->sAmount;
}

$sPageContents = NewTemplater::Render("payment/bitcoin", $locale->strings, array(
	"address"	=> $sPaymentMethod->sAddress,
	"amount"	=> Currency::Format("btc", $sAmount),
	"done-url"	=> "/pay/{$sPaymentRequest->sSubscription->sEmailAddress}/{$sPaymentRequest->sId}/{$sPaymentRequest->sKey}/bitcoin/done"
));
