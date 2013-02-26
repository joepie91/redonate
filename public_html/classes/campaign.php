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

class Campaign extends CPHPDatabaseRecordClass
{
	public $table_name = "campaigns";
	public $fill_query = "SELECT * FROM campaigns WHERE `Id` = :Id";
	public $verify_query = "SELECT * FROM campaigns WHERE `Id` = :Id";
	
	public $prototype = array(
		'string' => array(
			'Name'				=> "Name",
			'UrlName'			=> "UrlName"
		),
		'numeric' => array(
			'OwnerId'			=> "OwnerId",
			'DonationRate'			=> "DonationRate",
			'SubscriberCount'		=> "SubscriberCount",
			'MonthlyTotal'			=> "TotalMonthlyDonations",
			'MonthlyProjection'		=> "ProjectedMonthlyDonations",
			'PastMonthDonations'		=> "PastMonthDonations",
			'PastMonthNonDonations'		=> "PastMonthNonDonations",
			'PastMonthSubscriptions'	=> "PastMonthSubscriptions",
			'PastMonthUnsubscriptions'	=> "PastMonthUnsubscriptions"
		),
		'boolean' => array(
			'AllowOneTime'			=> "AllowOneTime",
			'HaveData'			=> "HaveData"
		),
		'timestamp' => array(
			'LastStatisticsUpdate'		=> "LastStatisticsUpdate",
			'CreationDate'			=> "CreationDate"
		),
		'user' => array(
			'Owner'				=> "OwnerId"
		)
	);
	
	public static function CheckIfUrlNameExists($urlname)
	{
		try
		{
			$result = Campaign::FindByUrlName($urlname);
			return true;
		}
		catch (NotFoundException $e)
		{
			return false;
		}
	}
	
	public static function FindByUrlName($urlname)
	{
		return self::CreateFromQuery("SELECT * FROM campaigns WHERE `UrlName` = :UrlName", array(':UrlName' => $urlname), 0, true);
	}
	
	public static function GenerateUrlName($name)
	{
		$found = false;
		$iteration = 0;
		$sUrlName = "";
		
		try
		{
			while(true)
			{
				$sUrlName = generate_urlname($name, $iteration);
				$result = Campaign::FindByUrlName($sUrlName);
				$iteration += 1;
			}
		}
		catch (NotFoundException $e)
		{
			/* Current UrlName is not in use */
		}
		
		return $sUrlName;
	}
	
	public function VerifyAdministratorAccess($userid)
	{
		return ($this->sOwnerId == $userid);
	}
	
	public function UpdateStatistics()
	{
		global $database;
		
		if($this->sLastStatisticsUpdate < time() - (60 * 5))
		{	
			/* Update subscriber count */
			if($result = $database->CachedQuery("SELECT COUNT(*) FROM subscriptions WHERE `CampaignId` = :CampaignId AND `Confirmed` = 1", array(":CampaignId" => $this->sId)))
			{
				$this->uSubscriberCount = $result->data[0]["COUNT(*)"];
			}
			
			/* Update total monthly donations */
			try
			{
				$sSubscriptions = Subscription::CreateFromQuery("SELECT * FROM subscriptions WHERE `CampaignId` = :CampaignId AND `Confirmed` = 1", array(":CampaignId" => $this->sId));
				$sTotalDonations = 0;
				
				foreach($sSubscriptions as $sSubscription)
				{
					$sTotalDonations += Currency::Convert("usd", $sSubscription->sCurrency, $sSubscription->sAmount);
				}
				
				$this->uMonthlyTotal = $sTotalDonations;
			}
			catch (NotFoundException $e)
			{
				$this->uMonthlyTotal = 0;
			}
			
			/* Update donation rate */
			try
			{
				$sDonationsAsked = LogEntry::CreateFromQuery("SELECT * FROM log_entries WHERE `CampaignId` = :CampaignId AND `Type` = :Type AND `Date` > DATE_SUB(NOW(), INTERVAL 1 MONTH)", 
					array(":CampaignId" => $this->sId, ":Type" => LogEntry::DONATION_ASKED));
					
				$have_data = true;
			}
			catch (NotFoundException $e)
			{
				/* We don't have any data to work from yet. */
				$sDonationsAsked = array();
				$have_data = false; 
			}
			
			if($have_data)
			{
				try
				{
					$sDonationsMade = LogEntry::CreateFromQuery("SELECT * FROM log_entries WHERE `CampaignId` = :CampaignId AND `Type` = :Type AND `Date` > DATE_SUB(NOW(), INTERVAL 1 MONTH)", 
						array(":CampaignId" => $this->sId, ":Type" => LogEntry::DONATION_MADE));
						
					$this->uDonationRate = (count($sDonationsMade) / count($sDonationsAsked)) * 100;
					$this->uHaveData = true;
				}
				catch (NotFoundException $e)
				{
					$sDonationsMade = array();
					$this->uDonationRate = 0;
					$this->uHaveData = false;
				}
			}
			else
			{
				$sDonationsMade = array();
				$this->uDonationRate = 100;
				$this->uHaveData = false;
			}
			
			/* Update projected monthly donations */
			$this->uMonthlyProjection = $this->uMonthlyTotal * ($this->uDonationRate / 100);
			
			/* Update past-month subscription count */ 
			if($result = $database->CachedQuery("SELECT COUNT(*) FROM log_entries WHERE `CampaignId` = :CampaignId AND `Type` = :Type AND `Date` > DATE_SUB(NOW(), INTERVAL 1 MONTH)", 
				array(":CampaignId" => $this->sId, ":Type" => LogEntry::SUBSCRIPTION_CONFIRMED)))
			{
				$this->uPastMonthSubscriptions = $result->data[0]["COUNT(*)"];
			}
			
			/* Update past-month unsubscription count */ 
			if($result = $database->CachedQuery("SELECT COUNT(*) FROM log_entries WHERE `CampaignId` = :CampaignId AND `Type` = :Type AND `Date` > DATE_SUB(NOW(), INTERVAL 1 MONTH)", 
				array(":CampaignId" => $this->sId, ":Type" => LogEntry::UNSUBSCRIPTION)))
			{
				$this->uPastMonthUnsubscriptions = $result->data[0]["COUNT(*)"];
			}
			
			/* Update past month donation count */
			$this->uPastMonthDonations = count($sDonationsMade);
			
			/* Update past month non-donation count */
			$this->uPastMonthNonDonations = count($sDonationsAsked) - count($sDonationsMade);
			
			$this->uLastStatisticsUpdate = time();
			$this->InsertIntoDatabase();
		}
	}
}
