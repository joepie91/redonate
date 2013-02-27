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

class PaymentMethod extends CPHPDatabaseRecordClass
{
	public $table_name = "payment_methods";
	public $fill_query = "SELECT * FROM payment_methods WHERE `Id` = :Id";
	public $verify_query = "SELECT * FROM payment_methods WHERE `Id` = :Id";
	
	public $prototype = array(
		'string' => array(
			'Address'		=> "Address",
			'CustomName'		=> "CustomName"
		),
		'numeric' => array(
			'Type'			=> "Type",
			'CampaignId'		=> "CampaignId"
		),
		'campaign' => array(
			'Campaign'		=> "Campaign"
		)
	);
	
	const PAYPAL = 1;
	const BITCOIN = 2;
	const IBAN = 3;
	
	public function GetLogo()
	{
		switch($this->sType)
		{
			case PaymentMethod::PAYPAL:
				return array("image" => "/static/images/paypal.png", "text" => "PayPal");
			case PaymentMethod::BITCOIN:
				return array("image" => "/static/images/bitcoin.png", "text" => "Bitcoin");
			case PaymentMethod::IBAN:
				return array("text" => "IBAN");
			case 0:
				return array("text" => $this->sCustomName);
			default:
				return array("text" => "Unknown");
		}
	}
	
	public function GenerateUrl($sRequest)
	{
		$sUrlName = $this->GetUrlName();
		return "http://redonate.net/pay/{$sRequest->sSubscription->sEmailAddress}/{$sRequest->sId}/{$sRequest->sKey}/{$sUrlName}";
	}
	
	public function GetName()
	{
		switch($this->sType)
		{
			case PaymentMethod::PAYPAL:
				return "PayPal";
			case PaymentMethod::BITCOIN:
				return "Bitcoin";
			case 0:
				return "{$this->sCustomName}";
			default:
				throw Exception("No valid payment method type.");
		}
	}
	
	public function GetUrlName()
	{
		switch($this->sType)
		{
			case PaymentMethod::PAYPAL:
				return "paypal";
			case PaymentMethod::BITCOIN:
				return "bitcoin";
			case 0:
				return "{$this->sId}";
			default:
				throw Exception("No valid payment method type.");
		}
	}
	
	public static function ValidateAddress($type, $address)
	{
		switch($type)
		{
			case PaymentMethod::PAYPAL:
				return filter_var($address, FILTER_VALIDATE_EMAIL);
			case PaymentMethod::BITCOIN:
				return (preg_match("/^[a-zA-Z1-9]{27,35}$/", $address) == true);
			default:
				return true;
		}
	}
	
	public static function CheckIfValidMethod($type)
	{
		return in_array($type, array(0, PaymentMethod::PAYPAL, PaymentMethod::BITCOIN));
	}
}
