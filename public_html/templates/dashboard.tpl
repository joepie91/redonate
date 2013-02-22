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
		<th>Campaign type</th>
		<th>Payment methods</th>
	</tr>
	{%foreach campaign in campaigns}
		<tr>
			<td>{%?campaign[name]}</td>
			<td></td>
			<td></td>
		</tr>
	{%/foreach}
</table>
