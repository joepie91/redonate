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

$sOtherSubscriptions = array();

foreach(Subscription::FindByEmail($sSubscription->sEmailAddress) as $sOtherSubscription)
{
	/* We don't want to add the currently visible subscription to the
	 * list of other subscriptions. */
	if($sOtherSubscription->sId != $sSubscription->sId)
	{
		if($sOtherSubscription->sIsConfirmed == false)
		{
			$sStatus = "Awaiting confirmation";
		}
		elseif($sOtherSubscription->sIsActive == true)
		{
			$sStatus = "Active";
		}
		else
		{
			$sStatus = "Cancelled";
		}
		
		$sOtherSubscriptions[] = array(
			"name"		=> $sOtherSubscription->sCampaign->sName,
			"amount"	=> Currency::Format($sOtherSubscription->sCurrency, $sOtherSubscription->sAmount),
			"key"		=> $sOtherSubscription->sSettingsKey,
			"status"	=> $sStatus
		);
	}
}

if($sSubscription->sIsConfirmed == false)
{
	$sStatus = "Awaiting confirmation";
}
elseif($sSubscription->sIsActive == true)
{
	$sStatus = "Active";
}
else
{
	$sStatus = "Cancelled";
}

$sPageTitle = "Manage your subscriptions";
$sPageContents = NewTemplater::Render("subscription/manage", $locale->strings, array(
	"name" 		=> $sSubscription->sCampaign->sName,
	"amount"	=> Currency::Format($sSubscription->sCurrency, $sSubscription->sAmount),
	"email"		=> $sSubscription->sEmailAddress,
	"key"		=> $sSubscription->sSettingsKey,
	"status"	=> $sStatus,
	"other"		=> $sOtherSubscriptions
));
