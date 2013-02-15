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
			'Name'		=> "Name",
			'UrlName'	=> "UrlName"
		),
		'numeric' => array(
			'OwnerId'	=> "UserId"
		),
		'boolean' => array(
			'AllowOneTime'	=> "AllowOneTime"
		),
		'user' => array(
			'Owner'		=> "Owner"
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
}
