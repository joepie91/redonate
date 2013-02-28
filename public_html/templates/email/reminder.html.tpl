<p>
	<strong>Hi there,</strong>
</p>

<p>
	This is your donation pledge reminder for this month. You pledged to 
	donate {%?amount} every month to {%?campaign-name}.
</p>

<p>
	To make your donation for this month, please use one of the following links:
</p>

<ul>
	{%foreach method in methods}
		<li><strong>{%?method[name]}:</strong> <a href="{%?method[url]}">{%?method[url]}</a></li>
	{%/foreach}
</ul>

<p>
	If you want to skip the donation for this month, then please click the
	following link so that we can record it in the statistics:
</p>

<p>
	<a href="{%?skip-url}">{%?skip-url}</a>
</p>

<p>
	Don't worry - the campaign administrator can't see who has donated, and
	who hasn't!
</p>

<p>
	If you have any further questions about ReDonate, feel free to reply to
	this e-mail. We read every e-mail, and reply to them personally.
</p>

<p>
	<em>- Sven Slootweg, ReDonate</em>
</p>

<hr>

<p>
If you want to cancel your donation pledge or change your settings, please visit
<a href="{%?unsubscribe-url}">{%?unsubscribe-url}</a>.
</p>
