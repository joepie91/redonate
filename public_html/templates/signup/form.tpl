<div class="formwrapper">
	<h2 class="spaced">Great! It'll only take a moment...</h2>

	{%if isempty|errors == false}
		<div class="errors">
			One or more problems occurred:
			<ul>
				{%foreach error in errors}
					<li>{%?error}</li>
				{%/foreach}
			</ul>
			Please correct these issues and submit the form again.
		</div>
	{%/if}

	<form method="post" action="/sign-up">
		<div class="formfield next-similar">
			 <label>Username</label>
			 {%input type="text" name="username"}
			 <div class="clear"></div>
		</div>

		<div class="formfield next-similar previous-similar">
			<label>Name (optional)</label>
			{%input type="text" name="displayname"}
			<div class="clear"></div>
		</div>

		<div class="formfield previous-similar">
			<label>E-mail address</label>
			{%input type="email" name="email"}
			<div class="note">we'll send you a verification e-mail</div>
		</div>

		<div class="formfield next-similar">
			<label>Password</label>
			{%input type="password" name="password"}
			<div class="clear"></div>
		</div>

		<div class="formfield previous-similar">
			<label>Password (again)</label>
			{%input type="password" name="password2"}
			<div class="note">at least 8 characters</div>
		</div>
		
		<div class="formfield submit">
			<button type="submit" class="green-button" name="submit" value="submit">Sign me up!</button>
		</div>
	</form>
</div>
