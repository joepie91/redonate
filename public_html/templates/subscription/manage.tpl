<h2 class="spaced">Manage your subscriptions</h2>

{%if isempty|notices == false}
	{%foreach notice in notices}
		<div class="notices">
			{%?notice}
		</div>
	{%/foreach}
{%/if}

<h3>This subscription</h3>

<ul>
	<li><strong>Campaign:</strong> {%?name}</li>
	<li><strong>E-mail address:</strong> {%?email}</li>
	<li><strong>Amount:</strong> {%?amount} per month</li>
	<li><strong>Status:</strong> {%?status}</li>
</ul>

{%if status == "Active"}
	<div class="toolbar">
		<a href="/manage/{%?email}/{%?key}/change-amount" class="button">Change monthly amount</a>
		<!-- <a href="/manage/{%?email}/{%?key}/change-email" class="button">Change e-mail address</a> -->
		<a href="/manage/{%?email}/{%?key}/unsubscribe" class="button">Unsubscribe</a>
		<div class="clear"></div>
	</div>
{%/if}

<h3 class="spaced">Other subscriptions for this address</h3>

<table>
	<tr>
		<th>Campaign</th>
		<th>Amount (p/ month)</th>
		<th>Status</th>
	</tr>
	{%foreach subscription in other}
		<tr class="clickable" data-url="/manage/{%?email}/{%?subscription[key]}">
			<td><a class="no-style" href="/manage/{%?email}/{%?subscription[key]}">{%?subscription[name]}</a></td>
			<td>{%?subscription[amount]}</td>
			<td>{%?subscription[status]}</td>
		</tr>
	{%/foreach}
</table>
