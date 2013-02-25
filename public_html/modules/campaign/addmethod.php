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

if(!empty($_POST['submit']))
{
	if(empty($_POST['address']))
	{
		flash_error("You did not enter a valid address or account ID.");
	}
	
	if(!isset($_POST['method']) || $_POST['method'] == "")
	{
		flash_error("You did not select a valid payment method.");
	}
	elseif($_POST['method'] == "0" && empty($_POST['customname']))
	{
		flash_error("You did not enter a valid name for the payment method.");
	}
	elseif(PaymentMethod::CheckIfValidMethod($_POST['method']) === false)
	{
		flash_error("You did not select a valid payment method.");
	}
	elseif(PaymentMethod::ValidateAddress($_POST['method'], $_POST['address']) === false)
	{
		flash_error("The address you entered is invalid.");
	}
	
	if(count(get_errors(false)) == 0)
	{
		$sPaymentMethod = new PaymentMethod(0);
		$sPaymentMethod->uType = $_POST['method'];
		$sPaymentMethod->uAddress = $_POST['address'];
		$sPaymentMethod->uCampaignId = $sCampaign->sId;
		
		if($_POST['method'] == 0)
		{
			$sPaymentMethod->uCustomName = $_POST['customname'];
		}
		
		$sPaymentMethod->InsertIntoDatabase();
		
		flash_notice("The payment method was successfully added.");
		redirect("/dashboard/{$sCampaign->uUrlName}");
	}
}

$sPageTitle = "Add payment method";
$sPageContents = NewTemplater::Render("campaign/addmethod", $locale->strings, array(
	"name"		=> $sCampaign->sName,
	"urlname"	=> $sCampaign->sUrlName
));
