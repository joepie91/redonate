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
	$sPaymentRequest = new PaymentRequest($router->uParameters[2]);
}
catch (NotFoundException $e)
{
	throw new RouterException("No such payment request exists.");
}

if($sPaymentRequest->sSubscription->uEmailAddress != $router->uParameters[1])
{
	throw new RouterException("The given e-mail address does not match the e-mail address for this payment request.");
}

if($sPaymentRequest->uKey != $router->uParameters[3])
{
	throw new RouterException("The given key does not match the key for this payment request.");
}

if($sPaymentRequest->sPaid === true)
{
	throw new RouterException("The payment request was already fulfilled.");
}

$sRouterAuthenticated = true;
