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
			'Address'		=> "Address"
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
			default:
				return array("text" => "Unknown");
		}
	}
}
