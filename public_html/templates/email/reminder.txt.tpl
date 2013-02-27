Hi there,

This is your donation pledge reminder for this month. You pledged to 
donate {%?amount} every month to {%?campaign-name}.

To make your donation for this month, use one of the following links:
{%foreach method in methods}
* {%?method[name]}: {%?method[url]}{%/foreach}

If you want to skip the donation for this month, then please click the
following link so that we can record it in the statistics:

{%?skip-url}

Don't worry - the campaign administrator can't see who has donated, and
who hasn't!

If you have any further questions about ReDonate, feel free to reply to
this e-mail. We read every e-mail, and reply to them personally.

- Sven Slootweg, ReDonate

-----

If you want to cancel your donation pledge, please visit
{%?unsubscribe-url}.
