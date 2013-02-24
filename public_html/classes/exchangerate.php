<?php
/*
 * projectname is more free software. It is licensed under the WTFPL, which
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

class ExchangeRate extends CPHPDatabaseRecordClass
{
	public $table_name = "exchange_rates";
	public $fill_query = "SELECT * FROM exchange_rates WHERE `Id` = :Id";
	public $verify_query = "SELECT * FROM exchange_rates WHERE `Id` = :Id";
	
	public $prototype = array(
		'string' => array(
			'Name'			=> "Name",
			'CurrencyCode'		=> "Code",
			'Symbol'		=> "Symbol"
		),
		'numeric' => array(
			'FromRate'		=> "From",
			'ToRate'		=> "To"
		),
		'timestamp' => array(
			'UpdateDate'		=> "Updated"
		)
	);
	
	public static function Update($code, $to)
	{
		$from = 1 / $to;
		
		try
		{
			$sRate = ExchangeRate::CreateFromQuery("SELECT * FROM exchange_rates WHERE `Code` = :Code", array(":Code" => $code), 0, true);
		}
		catch(NotFoundException $e)
		{
			$sRate = new ExchangeRate(0);
			$sRate->uCurrencyCode = $code;
		}
		
		$sRate->uToRate = $to;
		$sRate->uFromRate = $from;
		$sRate->uUpdateDate = time();
		$sRate->InsertIntoDatabase();
	}
}
