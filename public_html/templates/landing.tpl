<h2>Contribute to {%?project-name} monthly, no automatic charges.</h2>

{%if isempty|notices == false}
	{%foreach notice in notices}
		<div class="notices">
			{%?notice}
		</div>
	{%/foreach}
{%/if}

<div class="details">
	<h3>How does it work?</h3>
	<p class="leader">
		Most recurring services - even charities! - will automatically charge your account every month.
		ReDonate is different.
	</p>
	<p>
		When you subscribe to a ReDonate campaign, you only have to tell us how much you want to donate, and 
		your e-mail address. Every month, we will e-mail you to remind you of your pledge. The e-mail you 
		receive will include a list of payment methods, and an unsubscribe link.
	</p>
	<p>
		What this means in simple terms is that you'll <strong>never</strong> be automatically charged for a donation -
		you can decide to cancel your subscription at any time, without hassle, and without "oops, I forgot to cancel" 
		moments!
	</p>
	
	{%if can-donate-once == true}
		<h3>Why use ReDonate?</h3>
		<p>
			Many donors wish to donate to a cause at regular intervals, but do not want to have their account charged automatically.
			To take away the hassle of having to remember to donate every month, ReDonate was born. No need to remember a
			donation schedule, and no automatic charges either!
		</p>
	{%/if}
</div>
<div class="subscribe">
	<h3>Subscribe to a recurring donation</h3>
	{%if isempty|errors == false}
		{%foreach error in errors}
			<p class="error">
				{%?error}
			</p>
		{%/foreach}
	{%/if}
	<form method="post" action="/campaign/{%?urlname}/subscribe">
		<p>
			My e-mail address is...
			{%input type="text" name="email" id="field_email" placeholder="you@provider.com"}
		</p>
		<p>
			... and I'd like to pledge
			{%select name="currency" id="field_currency"}
				{%option value="usd" text="$"}
				{%option value="eur" text="€"}
				{%option value="btc" text="BTC"}
			{%/select}
			{%input type="text" name="amount" id="field_amount" value="5.00"}
			a month.
		</p>
		<p class="pledge-button">
			<button type="submit" class="green-button" id="button_subscribe">Pledge!</button>
		</p>
	</form>
	
	{%if can-donate-once == true}
		<h3 class="section" style="margin-bottom: 16px;">One-off donation</h3>
		{%foreach method in methods}
			<a class="no-style donate-once" href="/campaign/{%?urlname}/donate/{%?method[id]}">
				{%if isempty|method[image] == false}
					<img src="{%?method[image]}" alt="{%?method[text]}">
				{%else}
					<span class="logo">{%?method[text]}</span>
				{%/if}
			</a>
		{%/foreach}
	{%/if}
</div>
<div class="clear"></div>
<div class="more">
	<div class="wrapper">
		{%if can-donate-once == false}
			<h3>Why use ReDonate?</h3>
			<p>
				Many donors wish to donate to a cause at regular intervals, but do not want to have their account charged automatically.
				To take away the hassle of having to remember to donate every month, ReDonate was born. No need to remember a
				donation schedule, and no automatic charges either!
			</p>
		{%/if}
		
		<h3>Is this safe?</h3>
		<p class="leader">
			Short answer: Yes.
		</p>
		<p>
			The longer answer: ReDonate does not actually process any payments. You'll be using the same payment processor
			that you'd be using to donate otherwise - we're just here to remind you. We do not have access to any of your
			accounts, nor can we control where donations go.
		</p>
		<p>
			We also have no reason to sell or otherwise misuse your data. ReDonate is an entirely non-profit project. It's
			supported by the <a href="http://cryto.net/">Cryto Coding Collective</a>, a collective of developers that has
			been running off <a href="http://cryto.net/donate">donations</a> for nearly two years now. We won't use your
			e-mail address for anything other than sending you reminders, and every e-mail includes an unsubscribe link.
		</p>
	</div>
</div>
