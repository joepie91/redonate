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

class Subscription extends CPHPDatabaseRecordClass
{
	public $table_name = "subscriptions";
	public $fill_query = "SELECT * FROM subscriptions WHERE `Id` = :Id";
	public $verify_query = "SELECT * FROM subscriptions WHERE `Id` = :Id";
	
	public $prototype = array(
		'string' => array(
			'EmailAddress'		=> "EmailAddress",
			'ConfirmationKey'	=> "ConfirmationKey",
			'SettingsKey'		=> "SettingsKey",
			'Currency'		=> "Currency"
		),
		'numeric' => array(
			'CampaignId'		=> "CampaignId",
			'Amount'		=> "Amount"
		),
		'timestamp' => array(
			'SubscriptionDate'	=> "SubscriptionDate",
			'UnsubscriptionDate'	=> "UnsubscriptionDate",
			'LastEmailDate'		=> "LastEmail"
		),
		'boolean' => array(
			'IsConfirmed'		=> "Confirmed",
			'IsActive'		=> "Active"
		),
		'campaign' => array(
			'Campaign'		=> "CampaignId"
		)
	);
	
	public static function FindByEmail($email, $key = "", $expiry = 0)
	{
		if(empty($key))
		{
			return self::CreateFromQuery("SELECT * FROM subscriptions WHERE `EmailAddress` = :EmailAddress", array(':EmailAddress' => $email), $expiry);
		}
		else
		{
			return self::CreateFromQuery("SELECT * FROM subscriptions WHERE `EmailAddress` = :EmailAddress AND `SettingsKey` = :SettingsKey", 
				array(':EmailAddress' => $email, ':SettingsKey' => $key), $expiry, true);
		}
	}
	
	public function SendPaymentRequest()
	{
		/* Create a payment request */
		$sPaymentRequest = new PaymentRequest(0);
		$sPaymentRequest->uCurrency = $this->uCurrency;
		$sPaymentRequest->uAmount = $this->uAmount;
		$sPaymentRequest->uCampaignId = $this->uCampaignId;
		$sPaymentRequest->uSubscriptionId = $this->sId;
		$sPaymentRequest->uKey = random_string(16);
		$sPaymentRequest->uPaid = false;
		$sPaymentRequest->uDate = time();
		$sPaymentRequest->InsertIntoDatabase();
		
		/* Log an event */
		$sLogEntry = new LogEntry(0);
		$sLogEntry->uType = LogEntry::DONATION_ASKED;
		$sLogEntry->uIp = $_SERVER['REMOTE_ADDR'];
		$sLogEntry->uData = json_encode(array("payment_request" => $sPaymentRequest->sId));
		$sLogEntry->uCampaignId = $sPaymentRequest->sCampaign->sId;
		$sLogEntry->uDate = time();
		$sLogEntry->uSessionId = session_id();
		$sLogEntry->InsertIntoDatabase();
		
		/* Send an e-mail */
		$sEmail = $sPaymentRequest->GenerateEmail();
		send_mail($this->sEmailAddress, "Your monthly donation to {$this->sCampaign->sName}", $sEmail['text'], $sEmail['html']);
		
		/* Update the subscription to reflect the last sent e-mail */
		$this->uLastEmailDate = time();
		$this->InsertIntoDatabase();
	}
}
