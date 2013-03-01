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

if(!empty($_POST['submit']))
{
	if(empty($_POST['currency']))
	{
		flash_error("Please select a valid currency.");
	}
	
	if(empty($_POST['amount']) || preg_match("([0-9]*[.,][0-9]+|[0-9]+)", $_POST['amount']) == false)
	{
		flash_error("Please enter a valid amount.");
	}
	
	if(count(get_errors(false)) == 0)
	{
		$sSubscription->uAmount = str_replace(",", ".", $_POST['amount']);
		$sSubscription->uCurrency = $_POST['currency'];
		$sSubscription->InsertIntoDatabase();
		
		flash_notice("The monthly pledge amount for this subscription was successfully updated.");
		redirect("/manage/{$sSubscription->sEmailAddress}/{$sSubscription->sSettingsKey}");
	}
}

$sPageTitle = "Change pledge amount";
$sPageContents = NewTemplater::Render("subscription/change_amount", $locale->strings, array(
	"email"		=> $sSubscription->sEmailAddress,
	"key"		=> $sSubscription->sSettingsKey
));
