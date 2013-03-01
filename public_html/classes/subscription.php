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
}
