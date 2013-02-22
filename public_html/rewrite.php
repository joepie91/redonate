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

$_APP = true;
require("includes/base.php");

$sPageTitle = "";
$sPageContents = "";
$sError = "";

$router = new CPHPRouter();
$router->allow_slash = true;
$router->ignore_query = true;

$router->routes = array(
	0 => array(
		"^/$"						=> array(
									'target' => "modules/index.php",
									'_padded' => false
								),
		"^/sign-up$"					=> "modules/signup.php",
		"^/login$"					=> "modules/login.php",
		"^/logout/([a-zA-Z0-9]+)$"			=> "modules/logout.php",
		"^/confirm/(.+)/([a-zA-Z0-9]+)$"			=> "modules/confirm.php",
		"^/dashboard$"					=> "modules/dashboard.php",
		"^/campaign/([a-zA-Z0-9-]+)$"			=> "modules/landing.php",
		"^/campaign/([a-zA-Z0-9-]+)/subscribe$"		=> "modules/subscribe.php"
	)
);

try
{
	$router->RouteRequest();
}
catch (RouterException $e)
{
	http_status_code(404);
	$sPageTitle = "Page not found";
	$sPageContents = NewTemplater::Render("404", $locale->strings, array());
}

echo(NewTemplater::Render("layout", $locale->strings, array("contents" => $sPageContents, "title" => $sPageTitle, 
	"padded" => (isset($router->uVariables['padded']) ? $router->uVariables['padded'] : true))));
