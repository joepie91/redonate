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

$sErrors = array();

if(!empty($_POST['submit']))
{	
	if(empty($_POST['username']) || !preg_match("/^[a-zA-Z0-9-.]+$/", $_POST['username']))
	{
		$sErrors[] = "You did not enter a valid username. Your username can only contain a-z, A-Z, 0-9, dots, and dashes.";
	}
	elseif(User::CheckIfUsernameExists($_POST['username']) || User::CheckIfDisplayNameExists($_POST['username']))
	{
		$sErrors[] = "The username you entered is already in use. Please pick a different username.";
	}
	
	if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	{
		$sErrors[] = "You did not enter a valid e-mail address.";
	}
	elseif(User::CheckIfEmailExists($_POST['email']))
	{
		$sErrors[] = "The e-mail address you entered is already in use. Did you <a href=\"/forgot-password\">forget your password</a>?";
	}
	
	if(empty($_POST['password']) || strlen($_POST['password']) < 8)
	{
		$sErrors[] = "You did not enter a valid password. Your password has to be at least 8 characters.";
	}
	elseif(empty($_POST['password2']) || $_POST['password'] != $_POST['password2'])
	{
		$sErrors[] = "The passwords you entered did not match.";
	}
	
	if(!empty($_POST['displayname']) && User::CheckIfDisplayNameExists($_POST['displayname']))
	{
		$sErrors[] = "The (display) name you entered is already in use. Please pick a different name. You can also just use your nickname!";
	}
	
	if(empty($sErrors))
	{
		$sUser = new User(0);
		$sUser->uUsername = $_POST['username'];
		$sUser->uDisplayName = (!empty($_POST['displayname'])) ? $_POST['displayname'] : $_POST['username'];
		$sUser->uPassword = $_POST['password'];
		$sUser->uEmailAddress = $_POST['email'];
		$sUser->uActivationKey = random_string(16);
		$sUser->GenerateSalt();
		$sUser->GenerateHash();
		$sUser->InsertIntoDatabase();
		
		send_mail($_POST['email'], "Please confirm your registration at ReDonate.", 
			NewTemplater::Render("email/signup.txt", $locale->strings, array(
				"confirmation-url" => "http://redonate.cryto.net/confirm/{$sUser->sEmailAddress}/{$sUser->sActivationKey}/",
				"name" => $sUser->uDisplayName)), /* we don't want a HTML-entities-encoded version here */
			NewTemplater::Render("email/layout.html", $locale->strings, array(
				"contents" => NewTemplater::Render("email/signup.html", $locale->strings, array(
					"confirmation-url" => "http://redonate.cryto.net/confirm/{$sUser->sEmailAddress}/{$sUser->sActivationKey}/",
					"name" => $sUser->sDisplayName))
			))
		);
		
		$sPageContents = NewTemplater::Render("signup/success", $locale->strings, array());
		$sPageTitle = "Thanks for signing up!";
		return;
	}
}

$sPageContents = NewTemplater::Render("signup/form", $locale->strings, array('errors' => $sErrors));
$sPageTitle = "Sign up";
