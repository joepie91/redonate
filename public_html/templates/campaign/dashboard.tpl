<h2 class="spaced">Dashboard &gt; {%?name}</h2>

{%if isempty|notices == false}
	{%foreach notice in notices}
		<div class="notices">
			{%?notice}
		</div>
	{%/foreach}
{%/if}

<div class="dashboard-section">
	<h3 class="spaced">Your public campaign page URL</h3>
	<input class="permalink" type="text" value="http://redonate.net/campaign/{%?urlname}">
</div>

<div class="dashboard-section">
	<h3>Past month</h3>
	
	<div class="bar-graph">
		<div class="area unsubscribed" style="width: {%?unsubscriptions-percentage}%;"></div>
		<div class="area not-donated" style="width: {%?nondonations-percentage}%;"></div>
		<div class="area donated" style="width: {%?donations-percentage}%;"></div>
		<div class="area subscribed" style="width: {%?subscriptions-percentage}%;"></div>
		{%if statistics-available == false}
			<div class="no-stats">No statistics available yet.</div>
		{%/if}
	</div>
	
	<div class="graph-legend">
		<div class="item">
			<div class="box subscribed"></div>
			<div class="description">{%?subscriptions-amount} new subscriptions</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="box donated"></div>
			<div class="description">{%?donations-amount} donations</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="box not-donated"></div>
			<div class="description">{%?nondonations-amount} non-donations</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="box unsubscribed"></div>
			<div class="description">{%?unsubscriptions-amount} unsubscriptions</div>
			<div class="clear"></div>
		</div>
	</div>
	
	<div class="clear"></div>
</div>

<div class="dashboard-section">
	<div class="complex-header">
		<h3 class="spaced">Payment methods</h3>
		<a class="button" href="/dashboard/{%?urlname}/add-payment-method">Add method</a>
		<div class="clear"></div>
	</div>
	
	{%if isempty|payment-methods == false}
		<table class="payment-methods">
			{%foreach method in payment-methods}
				<tr>
					<td class="logo">
						{%if isempty|method[image] == false}
							<img class="logo" src="{%?method[image]}" alt="{%?method[text]}">
						{%else}
							<div class="logo">{%?method[text]}</div>
						{%/if}
					</td>
					<td class="address">
						{%?method[address]}
					</td>
					<td class="remove">
						<form method="post" action="/dashboard/{%?urlname}/remove-payment-method/{%?method[id]}">
							<button type="submit">Remove</button>
						</form>
					</td>
				</tr>
			{%/foreach}
		</table>
	{%else}
		<p>No payment methods have been added yet.</p>
	{%/if}
</div>
