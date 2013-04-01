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

if(strtolower($sPaymentRequest->sCurrency) == "btc")
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

if(filter_var($sPaymentMethod->sAddress, FILTER_VALIDATE_EMAIL))
{
	$target = "https://www.paypal.com/cgi-bin/webscr?business={$sQuotedRecipient}&cmd=_donations&item_name={$sQuotedName}&item_number={$sQuotedNumber}&currency_code={$sCurrency}&amount={$sAmount}&return={$sQuotedReturnUrl}";
}
else
{
	/* This is most likely a hosted button ID. We can only provide limited information in this case - we can really only set the item description. 
	 * Not sure if setting the return URL will work, but we might as well try. */
	$target = "https://www.paypal.com/cgi-bin/webscr?hosted_button_id={$sQuotedRecipient}&cmd=_s-xclick&item_name={$sQuotedName}&return={$sQuotedReturnUrl}";
}

redirect($target);
