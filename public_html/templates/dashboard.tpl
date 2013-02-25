<h2 class="spaced">Dashboard</h2>

{%if isempty|notices == false}
	{%foreach notice in notices}
		<div class="notices">
			{%?notice}
		</div>
	{%/foreach}
{%/if}

<table>
	<tr>
		<th>Name</th>
		<th>Type</th>
		<th class="icon"><img src="/static/images/icons/subscribers.png" alt="Amount of subscribers" title="Amount of subscribers"></th>
		<th class="icon"><img src="/static/images/icons/rate.png" alt="Percentage of subscribed donations actually made in the past month" title="Percentage of subscribed donations actually made in the past month"></th>
		<th class="icon"><img src="/static/images/icons/total.png" alt="Total amount of subscribed donations per month" title="Total amount of subscribed donations per month"></th>
		<th class="icon"><img src="/static/images/icons/projected.png" alt="Estimate of real donations per month, based on donation rate" title="Estimate of real donations per month, based on donation rate"></th>
		<th>Payment methods</th>
	</tr>
	{%foreach campaign in campaigns}
		<tr class="clickable" data-url="/dashboard/{%?campaign[urlname]}">
			<td class="name">{%?campaign[name]}</td>
			<td>
				{%if campaign[one-off] == false}
					Recurring
				{%else}
					Recurring &amp; One-off
				{%/if}
			</td>
			<td class="numeric">{%?campaign[subscribers]}</td>
			<td class="numeric">
				{%if campaign[have-data] == true}
					{%?campaign[rate]}%
				{%else}
					-
				{%/if}
			</td>
			<td class="numeric">{%?campaign[total]}</td>
			<td class="numeric total">{%?campaign[projection]}</td>
			<td class="payment-methods">
				{%foreach method in campaign[payment-methods]}
					{%if isempty|method[image] == false}
						<img class="logo thumb" src="{%?method[image]}" alt="{%?method[text]}">
					{%else}
						<div class="logo thumb">{%?method[text]}</div>
					{%/if}
				{%/foreach}
			</td>
		</tr>
	{%/foreach}
	<tr class="total">
		<td class="meta" colspan="2">Total</td>
		<td class="numeric">{%?total-subscribers}</td>
		<td class="numeric">{%?total-rate}%</td>
		<td class="numeric">{%?total-total}</td>
		<td class="numeric total">{%?total-projection}</td>
		<td class="payment-methods">
			
		</td>
	</tr>
</table>
