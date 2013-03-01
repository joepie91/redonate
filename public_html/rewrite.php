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
		"^/$"							=> array(
										'target' => "modules/index.php",
										'_padded' => false
									),
		"^/sign-up$"						=> "modules/signup.php",
		"^/login$"						=> "modules/login.php",
		"^/about$"						=> "modules/about.php",
		"^/logout/([a-zA-Z0-9]+)$"				=> "modules/logout.php",
		"^/confirm/(.+)/([a-zA-Z0-9]+)$"			=> "modules/confirm.php",
		"^/create$"						=> array(
			'target' => "modules/campaign/create.php",
			'authenticator' => "authenticators/user.php",
			'auth_error' => "modules/error/guest.php"
		),
		"^/dashboard$"						=> array(
			'target' => "modules/dashboard.php",
			'authenticator' => "authenticators/user.php",
			'auth_error' => "modules/error/guest.php"
		),
		"^/dashboard/([a-zA-Z0-9-]+)$"				=> array(
			'target' => "modules/campaign/dashboard.php",
			'authenticator' => "authenticators/user.php",
			'auth_error' => "modules/error/guest.php"
		),
		"^/dashboard/([a-zA-Z0-9-]+)/add-payment-method$"	=> array(
			'target' => "modules/campaign/addmethod.php",
			'authenticator' => "authenticators/user.php",
			'auth_error' => "modules/error/guest.php"
		),
		"^/dashboard/([a-zA-Z0-9-]+)/remove-payment-method/([0-9]+)$"	=> array(
			'target' => "modules/campaign/removemethod.php",
			'authenticator' => "authenticators/user.php",
			'auth_error' => "modules/error/guest.php"
		),
		"^/pay/(.+)/([0-9]+)/([a-zA-Z0-9]+)/(.+)/done$"		=> array(
			'target' => "modules/payment/notify_done.php",
			'authenticator' => "authenticators/payment.php",
			'auth_error' => "modules/error/nosuchpayment.php"
		),
		"^/pay/(.+)/([0-9]+)/([a-zA-Z0-9]+)/skip$"		=> array(
			'target' => "modules/payment/notify_skip.php",
			'authenticator' => "authenticators/payment.php",
			'auth_error' => "modules/error/nosuchpayment.php"
		),
		"^/pay/(.+)/([0-9]+)/([a-zA-Z0-9]+)/paypal$"		=> array(
			'target' => "modules/payment/paypal.php",
			'authenticator' => "authenticators/payment.php",
			'auth_error' => "modules/error/nosuchpayment.php"
		),
		"^/pay/(.+)/([0-9]+)/([a-zA-Z0-9]+)/bitcoin$"		=> array(
			'target' => "modules/payment/bitcoin.php",
			'authenticator' => "authenticators/payment.php",
			'auth_error' => "modules/error/nosuchpayment.php"
		),
		"^/pay/(.+)/([0-9]+)/([a-zA-Z0-9]+)/([0-9]+)$"		=> array(
			'target' => "modules/payment/other.php",
			'authenticator' => "authenticators/payment.php",
			'auth_error' => "modules/error/nosuchpayment.php"
		),
		"^/change/(.+)/([0-9]+)/([a-zA-Z0-9]+)$"	=> array(
			'target' => "modules/change.php",
			'authenticator' => "authenticators/change.php",
			'auth_error' => "modules/error/nosuchchange.php"
		),
		"^/manage/(.+?)/([a-zA-Z0-9]+)/change-amount$"		=> array(
			'target' => "modules/subscription/change_amount.php",
			'authenticator' => "authenticators/subscription.php",
			'auth_error' => "modules/error/nosuchsubscription.php"
		),
		"^/manage/(.+?)/([a-zA-Z0-9]+)/unsubscribe$"		=> array(
			'target' => "modules/subscription/unsubscribe.php",
			'authenticator' => "authenticators/subscription.php",
			'auth_error' => "modules/error/nosuchsubscription.php"
		),
		"^/manage/(.+?)/([a-zA-Z0-9]+)[.]?$"			=> array(
			'target' => "modules/subscription/manage.php",
			'authenticator' => "authenticators/subscription.php",
			'auth_error' => "modules/error/nosuchsubscription.php"
		),
		"^/campaign/([a-zA-Z0-9-]+)$"				=> "modules/landing.php",
		"^/campaign/([a-zA-Z0-9-]+)/subscribe$"			=> "modules/subscribe.php",
		"^/campaign/([a-zA-Z0-9-]+)/donate/([0-9]+)$"		=> "modules/donate.php",
		"^/thanks/([a-zA-Z0-9-]+)$"				=> "modules/thanks.php",
		"^/test$"						=> "modules/test.php"
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
