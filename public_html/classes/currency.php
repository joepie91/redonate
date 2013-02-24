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

class CurrencyConversionException extends Exception { }
class CurrencyFormattingException extends Exception { }

class Currency
{
	public static function Convert($target_currency, $original_currency, $amount)
	{
		$original_currency = strtoupper($original_currency);
		$target_currency = strtoupper($target_currency);
		
		try
		{
			$sOriginRate = ExchangeRate::CreateFromQuery("SELECT * FROM exchange_rates WHERE `Code` = :Code", array(":Code" => $original_currency), 300, true);
		}
		catch (NotFoundException $e)
		{
			throw new CurrencyConversionException("The specified origin currency is not a valid currency code.");
		}
		
		$sUsdRate = $amount * $sOriginRate->sFromRate;
		
		if($target_currency === "USD")
		{
			return $sUsdRate;
		}
		else
		{
			try
			{
				$sTargetRate = ExchangeRate::CreateFromQuery("SELECT * FROM exchange_rates WHERE `Code` = :Code", array(":Code" => $target_currency), 300, true);
			}
			catch (NotFoundException $e)
			{
				throw new CurrencyConversionException("The specified target currency is not a valid currency code.");
			}
			
			$sResult = $sUsdRate * $sTargetRate->sToRate;
			
			return $sResult;
		}
	}
	
	public static function Format($currency, $amount, $precision = 2)
	{
		try
		{
			$sExchangeRate = ExchangeRate::CreateFromQuery("SELECT * FROM exchange_rates WHERE `Code` = :Code", array(":Code" => $currency), 30, true);
		}
		catch (NotFoundException $e)
		{
			throw new CurrencyFormattingException("The specified currency is not a valid currency code.");
		}
		
		if($sExchangeRate->sSymbol == "")
		{
			return "{$sExchangeRate->sCurrencyCode} " . number_format($amount, $precision);
		}
		else
		{
			return "{$sExchangeRate->sSymbol}" . number_format($amount, $precision);
		}
	}
	
	public static function UpdateRates()
	{
		global $cphp_config;
		
		$json = json_decode(file_get_contents("http://openexchangerates.org/api/latest.json?app_id={$cphp_config->openexchangerates->app_id}"), true);
		$rates = $json["rates"];
		
		foreach($rates as $currency => $rate)
		{
			ExchangeRate::Update($currency, $rate);
		}
	}
}
