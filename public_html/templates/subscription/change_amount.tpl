<div class="formwrapper">
	<h2 class="spaced">Change pledge amount</h2>

	{%if isempty|errors == false}
		{%foreach error in errors}
			<div class="errors">
				{%?error}
			</div>
		{%/foreach}
	{%/if}
	
	<form method="post" action="/manage/{%?email}/{%?key}/change-amount">
		<div class="formfield">
			<label>Currency</label>
			{%select name="currency"}
				{%option value="usd" text="US Dollar"}
				{%option value="eur" text="Euro"}
				{%option value="btc" text="Bitcoin"}
			{%/select}
		</div>
		<div class="formfield">
			<label>Amount per month</label>
			{%input type="text" name="amount"}
			<div class="clear"></div>
		</div>
		<div class="formfield submit">
			<button type="submit" name="submit" value="submit">Save changes</button>
		</div>
	</form>
</div>
