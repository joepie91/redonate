<h2 class="spaced">Dashboard &gt; {%?name}</h2>

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
	<h3>Payment methods</h3>
	
	{%foreach method in payment-methods}
		{%if isempty|method[image] == false}
			<img class="logo" src="{%?method[image]}" alt="{%?method[text]}">
		{%else}
			<div class="logo">{%?method[text]}</div>
		{%/if}
	{%/foreach}
</div>
