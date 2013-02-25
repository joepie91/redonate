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
	if(empty($_POST['name']))
	{
		flash_error("You did not enter a valid campaign name.");
	}
	
	if(count(get_errors(false)) == 0)
	{
		$sCampaign = new Campaign(0);
		$sCampaign->uName = $_POST['name'];
		$sCampaign->uOwnerId = $sCurrentUser->sId;
		$sCampaign->uCreationDate = time();
		$sCampaign->uAllowOneTime = isset($_POST['allow_once']);
		$sCampaign->uUrlName = Campaign::GenerateUrlName($_POST['name']);
		$sCampaign->InsertIntoDatabase();
		
		flash_notice("Your campaign was successfully created. You should add a payment method now.");
		redirect("/dashboard/{$sCampaign->uUrlName}");
	}
}

$sPageTitle = "Create new campaign";
$sPageContents = NewTemplater::Render("campaign/create", $locale->strings, array());
