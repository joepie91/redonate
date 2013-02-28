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

class PaymentRequest extends CPHPDatabaseRecordClass
{
	public $table_name = "payment_requests";
	public $fill_query = "SELECT * FROM payment_requests WHERE `Id` = :Id";
	public $verify_query = "SELECT * FROM payment_requests WHERE `Id` = :Id";
	public $query_cache = 0;
	
	public $prototype = array(
		'string' => array(
			'Currency'		=> "Currency",
			'Key'			=> "Key"
		),
		'numeric' => array(
			'CampaignId'		=> "CampaignId",
			'SubscriptionId'	=> "SubscriptionId",
			'Amount'		=> "Amount"
		),
		'boolean' => array(
			'Paid'			=> "Paid"
		),
		'timestamp' => array(
			'IssueDate'		=> "Date"
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
		
		$sMethods = array();
		
		foreach($this->sCampaign->GetPaymentMethods() as $sPaymentMethod)
		{
			$sMethods[] = array(
				"name" => $sPaymentMethod->GetName(),
				"url" => $sPaymentMethod->GenerateUrl($this)
			);
		}
		
		$sText = NewTemplater::Render("email/reminder.txt", $locale->strings, array(
			"campaign-name" => $this->sCampaign->sName,
			"amount" => Currency::Format($this->sCurrency, $this->sAmount),
			"skip-url" => "http://redonate.net/pay/{$this->sSubscription->sEmailAddress}/{$this->sId}/{$this->sKey}/skip",
			"unsubscribe-url" => "http://redonate.com/manage/{$this->sSubscription->sEmailAddress}/{$this->sSubscription->sSettingsKey}",
			"methods" => $sMethods
		));
		
		$sHtml = NewTemplater::Render("email/layout.html", $locale->strings, array(
			"contents" => NewTemplater::Render("email/reminder.html", $locale->strings, array(
				"campaign-name" => $this->sCampaign->sName,
				"amount" => Currency::Format($this->sCurrency, $this->sAmount),
				"skip-url" => "http://redonate.net/pay/{$this->sSubscription->sEmailAddress}/{$this->sId}/{$this->sKey}/skip",
				"unsubscribe-url" => "http://redonate.com/manage/{$this->sSubscription->sEmailAddress}/{$this->sSubscription->sSettingsKey}",
				"methods" => $sMethods
			))
		));
		
		return array("text" => $sText, "html" => $sHtml);
	}
}
