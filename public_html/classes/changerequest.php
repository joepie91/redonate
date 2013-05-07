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

class ChangeRequest extends CPHPDatabaseRecordClass
{
	public $table_name = "change_requests";
	public $fill_query = "SELECT * FROM change_requests WHERE `Id` = :Id";
	public $verify_query = "SELECT * FROM change_requests WHERE `Id` = :Id";
	
	public $prototype = array(
		'string' => array(
			'OldCurrency'		=> "OldCurrency",
			'NewCurrency'		=> "NewCurrency",
			'Key'			=> "Key"
		),
		'numeric' => array(
			'SubscriptionId'	=> "SubscriptionId",
			'CampaignId'		=> "CampaignId",
			'OldAmount'		=> "OldAmount",
			'NewAmount'		=> "NewAmount"
		),
		'boolean' => array(
			'IsConfirmed'		=> "Confirmed"
		),
		'timestamp' => array(
			'Date'			=> "Date"
		),
		'campaign' => array(
			'Campaign'		=> "CampaignId"
		),
		'subscription' => array(
			'Subscription'		=> "SubscriptionId"
		)
	);
	
	public function GenerateEmail()
	{
		global $locale;
		
		$sText = NewTemplater::Render("email/change.txt", $locale->strings, array(
			"campaign-name" => $this->sCampaign->sName,
			"confirmation-url" => "http://redonate.net/change/{$this->sSubscription->sEmailAddress}/{$this->sId}/{$this->sKey}",
			"unsubscribe-url" => "http://redonate.net/manage/{$this->sSubscription->sEmailAddress}/{$this->sSubscription->sSettingsKey}",
			"old" => Currency::Format($this->sOldCurrency, $this->sOldAmount),
			"new" => Currency::Format($this->sNewCurrency, $this->sNewAmount)
		));
		
		$sHtml = NewTemplater::Render("email/layout.html", $locale->strings, array(
			"contents" => NewTemplater::Render("email/change.html", $locale->strings, array(
				"campaign-name" => $this->sCampaign->sName,
				"confirmation-url" => "http://redonate.net/change/{$this->sSubscription->sEmailAddress}/{$this->sId}/{$this->sKey}",
				"unsubscribe-url" => "http://redonate.net/manage/{$this->sSubscription->sEmailAddress}/{$this->sSubscription->sSettingsKey}",
				"old" => Currency::Format($this->sOldCurrency, $this->sOldAmount),
				"new" => Currency::Format($this->sNewCurrency, $this->sNewAmount)
			))
		));
		
		return array("text" => $sText, "html" => $sHtml);
	}
}
