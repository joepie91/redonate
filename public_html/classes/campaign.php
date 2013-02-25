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
			'Name'			=> "Name",
			'UrlName'		=> "UrlName"
		),
		'numeric' => array(
			'OwnerId'		=> "OwnerId",
			'DonationRate'		=> "DonationRate",
			'SubscriberCount'	=> "SubscriberCount",
			'MonthlyTotal'		=> "TotalMonthlyDonations",
			'MonthlyProjection'	=> "ProjectedMonthlyDonations"
		),
		'boolean' => array(
			'AllowOneTime'		=> "AllowOneTime",
			'HaveData'		=> "HaveData"
		),
		'timestamp' => array(
			'LastStatisticsUpdate'	=> "LastStatisticsUpdate"
		),
		'user' => array(
			'Owner'			=> "OwnerId"
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
					$this->uDonationRate = 0;
					$this->uHaveData = false;
				}
			}
			else
			{
				$this->uDonationRate = 100;
				$this->uHaveData = false;
			}
			
			/* Update projected monthly donations */
			$this->uMonthlyProjection = $this->uMonthlyTotal * ($this->uDonationRate / 100);
			
			$this->uLastStatisticsUpdate = time();
			$this->InsertIntoDatabase();
		}
	}
}
