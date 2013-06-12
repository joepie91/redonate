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

if(!empty($_POST['default_currency']) && in_array($_POST['default_currency'], array("usd", "eur", "btc"))) /* TODO: Allow other currencies */
{
	$sCampaign->uDefaultCurrency = $_POST['default_currency'];
}

if(isset($_POST['default_amount']))
{
	if(preg_match("/^([0-9]*[.,][0-9]+|[0-9]+)$/", $_POST['default_amount']) == false)
	{
		flash_error("You did not enter a valid default amount.");
	}
	else
	{
		$sCampaign->uDefaultAmount = $_POST['default_amount'];
	}
}

if(count(get_errors(false)) == 0)
{
	$sCampaign->InsertIntoDatabase();
	flash_notice("Settings successfully changed.");
}

redirect("/dashboard/{$sCampaign->sUrlName}");

