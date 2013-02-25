<h2 class="spaced">Dashboard &gt; {%?name}</h2>

{%if isempty|notices == false}
	{%foreach notice in notices}
		<div class="notices">
			{%?notice}
		</div>
	{%/foreach}
{%/if}

<div class="dashboard-section">
	<h3>Past month</h3>
	
	<div class="bar-graph">
		<div class="area unsubscribed" style="width: 12%;"></div>
		<div class="area not-donated" style="width: 16%;"></div>
		<div class="area donated" style="width: 49%;"></div>
		<div class="area subscribed" style="width: 23%;"></div>
	</div>
	
	<div class="graph-legend">
		<div class="item">
			<div class="box subscribed"></div>
			<div class="description">23 new subscriptions</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="box donated"></div>
			<div class="description">49 donations</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="box not-donated"></div>
			<div class="description">16 non-donations</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="box unsubscribed"></div>
			<div class="description">12 unsubscriptions</div>
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
