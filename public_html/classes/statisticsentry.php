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

class StatisticsEntry extends CPHPDatabaseRecordClass
{
	public $table_name = "statistics_entries";
	public $fill_query = "SELECT * FROM statistics_entries WHERE `Id` = :Id";
	public $verify_query = "SELECT * FROM statistics_entries WHERE `Id` = :Id";
	
	public $prototype = array(
		'numeric' => array(
			'CampaignId'			=> "CampaignId",
			'SubscriberCount'		=> "SubscriberCount",
			'TotalMonthlyDonations'		=> "TotalMonthlyDonations",
			'ProjectedMonthlyDonations'	=> "ProjectedMonthlyDonations",
			'PastMonthDonations'		=> "PastMonthDonations",
			'PastMonthAmount'		=> "PastMonthAmount",
			'PastMonthNonDonations'		=> "PastMonthNonDonations",
			'PastMonthNonAmount'		=> "PastMonthNonAmount",
			'PastMonthSubscriptions'	=> "PastMonthSubscriptions",
			'PastMonthUnsubscriptions'	=> "PastMonthUnsubscriptions",
			'DonationRate'			=> "DonationRate"
		),
		'timestamp' => array(
			'Date'				=> "Date"
		),
		'boolean' => array(
			'HaveData'			=> "HaveData",
			'AllowOneTime'			=> "AllowOneTime"
		),
		'campaign' => array(
			'Campaign'			=> "Campaign"
		)
	);
}
