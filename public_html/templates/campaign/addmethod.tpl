<div class="formwrapper">
	<h2 class="spaced">Add payment method for {%?name}</h2>
	
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

	<form method="post" action="/dashboard/{%?urlname}/add-payment-method">
		<div class="formfield">
			 <label>Payment method</label>
			 {%select name="method" id="field_method"}
				{%option value="1" text="PayPal"}
				{%option value="2" text="Bitcoin"}
				{%option value="0" text="Other..."}
			 {%/select}
			 <div class="clear"></div>
		</div>
		
		<div class="formfield conditional" data-conditional-element="field_method" data-conditional-value="0">
			<label>Custom name</label>
			{%input type="text" name="customname"}
			<div class="clear"></div>
		</div>
		
		<div class="formfield">
			<label>Address / ID</label>
			{%input type="text" name="address"}
			<div class="clear"></div>
		</div>

		<div class="formfield submit">
			<button type="submit" name="submit" value="submit">Add</button>
		</div>
	</form>
</div>
