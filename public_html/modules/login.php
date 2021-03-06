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

if(!empty($_SESSION['user_id']))
{
	flash_notice("You are already logged in.");
	redirect("/dashboard");
}

if(!empty($_POST['submit']))
{
	if(empty($_POST['username']))
	{
		flash_error("You did not enter a username.");
	}
	elseif(empty($_POST['password']))
	{
		flash_error("You did not enter a password.");
	}
	else
	{
		try
		{
			$sUser = User::CreateFromQuery("SELECT * FROM users WHERE `Username` = :Username", array(":Username" => $_POST['username']), 0, true);
			
			if($sUser->VerifyPassword($_POST['password']))
			{
				$sUser->Authenticate();
				flash_notice("Welcome back, {$sUser->sDisplayName}!");
				redirect("/dashboard");
			}
			else
			{
				flash_error("The password you entered is incorrect. Did you <a href=\"/forgot-password\">forget your password</a>?");
			}
		}
		catch (NotFoundException $e)
		{
			flash_error("That username does not exist.");
		}
	}
}

$sPageContents = NewTemplater::Render("login/form", $locale->strings, array());
$sPageTitle = "Login";
