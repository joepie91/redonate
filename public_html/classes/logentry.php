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

class LogEntry extends CPHPDatabaseRecordClass
{
	public $table_name = "log_entries";
	public $fill_query = "SELECT * FROM log_entries WHERE `Id` = :Id";
	public $verify_query = "SELECT * FROM log_entries WHERE `Id` = :Id";
	
	public $prototype = array(
		'string' => array(
			'Ip'			=> "Ip",
			'SessionId'		=> "SessionId",
			'Data'			=> "Data"
		),
		'numeric' => array(
			'Type'			=> "Type",
			'CampaignId'		=> "CampaignId"
		),
		'timestamp' => array(
			'Date'			=> "Date"
		),
		'campaign' => array(
			'Campaign'		=> "Campaign"
		)
	);
	
	const PAGELOAD = 1;
	const SUBSCRIPTION = 2;
	const DONATION_ASKED = 3;
	const DONATION_MADE = 4;
	const UNSUBSCRIPTION = 5;
	const SUBSCRIPTION_CONFIRMED = 6;
}
