<div class="formwrapper">
	<h2 class="spaced">Donate to {%?campaign-name} once using {%?method-name}.</h2>

	{%if isempty|errors == false}
		{%foreach error in errors}
			<div class="errors">
				{%?error}
			</div>
		{%/foreach}
	{%/if}
	
	<form method="post" action="/campaign/{%?urlname}/donate/{%?method-id}">
		<div class="formfield">
			<label>Currency</label>
			{%select name="currency"}
				{%option value="usd" text="US Dollar"}
				{%option value="eur" text="Euro"}
				{%option value="btc" text="Bitcoin"}
			{%/select}
		</div>
		<div class="formfield">
			<label>Amount</label>
			{%input type="text" name="amount"}
			<div class="clear"></div>
		</div>
		<div class="formfield submit">
			<button type="submit" name="submit" value="submit">Donate</button>
		</div>
	</form>
</div>
