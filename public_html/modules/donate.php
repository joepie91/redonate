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
	$sCampaign = Campaign::FindByUrlName($router->uParameters[1]);
}
catch (NotFoundException $e)
{
	/* TODO: 404 via RouterException */
	throw new RouterException("No such campaign.");
}

try
{
	$sPaymentMethod = new PaymentMethod($router->uParameters[2]);
}
catch (NotFoundException $e)
{
	throw new RouterException("No such payment method.");
}

if($sPaymentMethod->sCampaignId !== $sCampaign->sId)
{
	throw new RouterException("Payment method does not belong to campaign.");
}

switch($sPaymentMethod->sType)
{
	case PaymentMethod::PAYPAL:
		$sMethodName = "PayPal";
		break;
	case PaymentMethod::BITCOIN:
		$sMethodName = "Bitcoin";
		break;
	default:
		$sMethodName = $sPaymentMethod->sCustomName;
		break;
}

if(!empty($_POST['submit']))
{
	if(empty($_POST['currency']))
	{
		flash_error("You did not select a valid currency.");
	}
	
	if(empty($_POST['amount']))
	{
		flash_error("You did not enter a valid amount.");
	}
	
	if(count(get_errors(false)) == 0)
	{
		switch($sPaymentMethod->sType)
		{
			case PaymentMethod::PAYPAL:
				if($sPaymentRequest->sCurrency == "btc")
				{
					$sCurrency = urlencode("USD");
					$sAmount = round(Currency::Convert("usd", "btc", $_POST['amount']), 2);
				}
				else
				{
					$sCurrency = urlencode(strtoupper($_POST['currency']));
					$sAmount = urlencode($_POST['amount']);
				}

				$sQuotedRecipient = urlencode($sPaymentMethod->sAddress);
				$sQuotedName = urlencode("{$sCampaign->sName} (via ReDonate.net)");
				$sQuotedNumber = urlencode("0");
				$sQuotedReturnUrl = urlencode("http://redonate.net/thanks/{$sCampaign->sUrlName}");
				
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
				return;
			case PaymentMethod::BITCOIN:
				if($sPaymentRequest->sCurrency != "btc")
				{
					$sAmount = Currency::Convert("btc", $_POST['currency'], $_POST['amount']);
				}
				else
				{
					$sAmount = htmlspecialchars($_POST['amount']);
				}

				$sPageContents = NewTemplater::Render("payment/bitcoin", $locale->strings, array(
					"address"	=> $sPaymentMethod->sAddress,
					"amount"	=> Currency::Format("btc", $sAmount),
					"done-url"	=> "/thanks/{$sCampaign->sUrlName}"
				));
				return;
			default:
				$sPageContents = NewTemplater::Render("payment/other", $locale->strings, array(
					"name"		=> $sPaymentMethod->sCustomName,
					"address"	=> $sPaymentMethod->sAddress,
					"amount"	=> Currency::Format($_POST['currency'], $_POST['amount']),
					"done-url"	=> "/thanks/{$sCampaign->sUrlName}"
				));
				return;
		}
	}
}

$sPageTitle = "Donate to {$sCampaign->sName} once";
$sPageContents = NewTemplater::Render("donate", $locale->strings, array(
	"campaign-name"	=> $sCampaign->sName,
	"method-id" => $sPaymentMethod->sId,
	"urlname" => $sCampaign->sUrlName,
	"method-name" => $sMethodName
));

