<div class="formwrapper">
	<h2 class="spaced">Unsubscribe</h2>

	{%if isempty|errors == false}
		{%foreach error in errors}
			<div class="errors">
				{%?error}
			</div>
		{%/foreach}
	{%/if}
	
	<form method="post" action="/manage/{%?email}/{%?key}/unsubscribe">
		<div class="formfield">
			<p>
				Are you sure you want to unsubscribe from your pledge to {%?name}?
				You will no longer be reminded every month.
			</p>
		</div>
		<div class="formfield" style="text-align: center;">
			<button type="submit" name="submit" value="submit">Yes, unsubscribe me</button>
		</div>
	</form>
</div>
