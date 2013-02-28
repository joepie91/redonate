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
	$sPaymentMethod = $sPaymentRequest->sCampaign->GetPaymentMethod(PaymentMethod::PAYPAL);
}
catch (NotFoundException $e)
{
	throw new RouterException("No such payment method found.");
}

if($sPaymentRequest->sCurrency == "btc")
{
	$sCurrency = urlencode("USD");
	$sAmount = round(Currency::Convert("usd", "btc", $sPaymentRequest->sAmount), 2);
}
else
{
	$sCurrency = urlencode(strtoupper($sPaymentRequest->sCurrency));
	$sAmount = urlencode($sPaymentRequest->sAmount);
}

$sQuotedRecipient = urlencode($sPaymentMethod->sAddress);
$sQuotedName = urlencode("{$sPaymentRequest->sCampaign->sName} (via ReDonate.net)");
$sQuotedNumber = urlencode("{$sPaymentRequest->sId}");
$sQuotedReturnUrl = urlencode("http://redonate.net/pay/{$sPaymentRequest->sSubscription->sEmailAddress}/{$sPaymentRequest->sId}/{$sPaymentRequest->sKey}/paypal/done");
redirect("https://www.paypal.com/cgi-bin/webscr?business={$sQuotedRecipient}&cmd=_donations&item_name={$sQuotedName}&item_number={$sQuotedNumber}&currency_code={$sCurrency}&amount={$sAmount}&return={$sQuotedReturnUrl}");
