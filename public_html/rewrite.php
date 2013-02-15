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

$_CPHP = true;
$_CPHP_CONFIG = "../config.json";
require("cphp/base.php");
$_APP = true;

function __autoload($class_name) 
{
	global $_APP;
	
	$class_name = str_replace("\\", "/", strtolower($class_name));
	require_once("classes/{$class_name}.php");
}

$sPageTitle = "";
$sPageContents = "";

$router = new CPHPRouter();
$router->allow_slash = true;
$router->ignore_query = true;

$router->routes = array(
	0 => array(
		"^/$"						=> "modules/index.php",
		"^/register/$"					=> "modules/register.php",
		"^/login/$"					=> "modules/login.php",
		"^/campaign/([a-zA-Z0-9-]+)"			=> "modules/landing.php",
		"^/campaign/([a-zA-Z0-9-]+)/subscribe"		=> "modules/subscribe.php",
	)
);

$router->RouteRequest();

echo(NewTemplater::Render("layout", $locale->strings, array("contents" => $sPageContents, "title" => $sPageTitle)));
