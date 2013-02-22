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

require_once('lib/swiftmailer/swift_required.php');

function autoload_redonate($class_name) 
{
	global $_APP;
	
	$class_name = str_replace("\\", "/", strtolower($class_name));
	
	if(file_exists("classes/{$class_name}.php"))
	{
		require_once("classes/{$class_name}.php");
	}
}

spl_autoload_register(autoload_redonate);

function send_mail($to, $subject, $text, $html)
{
	global $mail_transport, $cphp_config;
	$sMessage = Swift_Message::newInstance();
	$sMessage->setSubject($subject);
	$sMessage->setTo($to);
	$sMessage->setFrom($cphp_config->smtp->from);
	$sMessage->setBody($text);
	$sMessage->addPart($html, "text/html");
	
	echo("<div style=\"border: 1px solid black; padding: 8px; background-color: white; margin: 8px; margin-bottom: 24px;\">
		<div style=\"font-size: 14px;\">
			<strong>From:</strong> {$cphp_config->smtp->from}<br>
			<strong>To:</strong> {$to}<br>
			<strong>Subject:</strong> {$subject}
		</div>
		<hr>
		<pre class=\"debug\">{$text}</pre>
		<hr>
		<div>
			{$html}
		</div>
	</div>");
	
	//$mail_transport->send($sMessage);
}


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
		"^/campaign/([a-zA-Z0-9-]+)$"			=> "modules/landing.php",
		"^/campaign/([a-zA-Z0-9-]+)/subscribe$"		=> "modules/subscribe.php",
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
