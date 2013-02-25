<h2 class="spaced">Create new campaign</h2>

{%if isempty|errors == false}
	<div class="errors">
		One or more errors occurred:
		<ul>
			{%foreach error in errors}
				<li>{%?error}</li>
			{%/foreach}
		</ul>
	</div>
{%/if}

<div class="formwrapper">
	<form method="post" action="/create" class="wide">
		<div class="formfield">
			<label>Campaign name</label>
			{%input type="text" name="name"}
			<div class="clear"></div>
		</div>
		
		<div class="formfield">
			<label>Allow one-off donations</label>
			{%input type="checkbox" name="allow_once"}
			<div class="clear"></div>
		</div>
		
		<div class="formfield submit">
			<button type="submit" name="submit" value="submit">Create</button>
		</div>
	</form>
</div>
