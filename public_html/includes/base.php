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

$_CPHP = true;
$_CPHP_CONFIG = "../config.json";
require("cphp/base.php");

/* Autoloader configuration */
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

/* Set global templater variables */
NewTemplater::SetGlobalVariable("logged-in", !empty($_SESSION['user_id']));

if(!empty($_SESSION['user_id']))
{
	$sCurrentUser = new User($_SESSION['user_id']);
	
	$sCurrentUser->SetGlobalVariables();
	
	NewTemplater::SetGlobalVariable("logout-key", $_SESSION['logout_key']);
}

NewTemplater::RegisterVariableHook("errors", "get_errors");
NewTemplater::RegisterVariableHook("notices", "get_notices");

function get_errors($fetch)
{
	if(isset($_SESSION['errors']))
	{
		$errors = $_SESSION['errors'];
		
		if($fetch === true)
		{
			/* We only want to clear out errors if a call to
			 * actually retrieve the errors was made, not just
			 * something like an isempty. */
			$_SESSION['errors'] = array();
		}
		
		return $errors;
	}
	else
	{
		return array();
	}
}

function get_notices($fetch)
{
	if(isset($_SESSION['notices']))
	{
		$notices = $_SESSION['notices'];
		
		if($fetch === true)
		{
			$_SESSION['notices'] = array();
		}
		
		return $notices;
	}
	else
	{
		return array();
	}
}

function flash_error($message)
{
	$_SESSION['errors'][] = $message;
}

function flash_notice($message)
{
	$_SESSION['notices'][] = $message;
}

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
