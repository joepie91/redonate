<div class="formwrapper">
	<h2>Donate using {%?name}</h2>

	<p>
		Please send <strong>{%?amount}</strong> to this address or account number:<br>
		<strong>{%?address}</strong>.
	</p>

	<p>
		We cannot automatically detect when you've sent your donation. To help us improve our statistics,
		please click the button below after you've donated.
	</p>

	<p>
		<form method="get" action="{%?done-url}" style="text-align: center;">
			<button type="submit">I've sent my donation</button>
		</form>
	</p>
</div>
