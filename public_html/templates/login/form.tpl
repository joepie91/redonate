<div class="formwrapper narrow">
	<h2 class="spaced">Login to your account</h2>

	{%if isempty|error == false}
		<div class="errors">
			{%?error}
		</div>
	{%/if}

	<form method="post" action="/login" class="narrow">
		<div class="formfield">
			 <label>Username</label>
			 {%input type="text" name="username"}
			 <div class="clear"></div>
		</div>
		
		<div class="formfield">
			<label>Password</label>
			{%input type="password" name="password"}
			<div class="clear"></div>
		</div>

		<div class="formfield submit">
			<button type="submit" name="submit" value="submit">Login</button>
		</div>
	</form>
</div>
