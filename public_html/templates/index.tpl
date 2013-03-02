<div class="padding">
	<h2>ReDonate is recurring contributions, done right.</h2>
</div>

<div class="intro">
	<img src="/static/images/intro.png">
</div>
<div class="padding">
	{%if isempty|notices == false}
		{%foreach notice in notices}
			<div class="notices">
				{%?notice}
			</div>
		{%/foreach}
	{%/if}

	<div class="col1">
		<h3>Why use ReDonate?</h3>
		
		<p>
			You just made an awesome website. Or maybe you made an album, or even a movie! You really want
			to make it available to others for free, but you don't get donations very often. 
		</p>
		
		<p>
			<strong>What if your fans are simply <em>forgetting</em> to donate?</strong>
		</p>
		
		<p>
			Maybe you've considered recurring donations. But on the other hand, would you really want to automatically take
			away money from your users and fans? If they have to jump through hoops to get rid of it... is it really a donation?
		</p>
		
		<p>
			<strong>We're here to help.</strong> We'll allow your users and fans to subscribe to monthly donations, while
			still keeping them <em>100% voluntary</em>. A donor can unsubscribe or choose not to donate at any time. And
			we'll give you a neat statistics page to keep track of what's going on :)
		</p>
	</div>
	<div class="col2">
		<h3>Here's what you get:</h3>
		
		<ul>
			<li><strong>100% free! No fees, no paid memberships.</strong></li>
			<li>For developers, creators, artists, fundraisers, charity workers, and quite literally everyone else.</li>
			<li>Have a more predictable income, from donations.</li>
			<li>Keep your donations <em>really</em> voluntary.</li>
			<li>Allow your donors to unsubscribe at any time, no commitments or hoops to jump through.</li>
		</ul>
		<ul>
			<li>No restrictions on payment methods or currencies.</li>
			<li>Yes, we do Bitcoin as well.</li>
			<li>No restrictions on what your campaign is about.</li>
			<li>Completely safe. We do not process any transactions ourselves, and do not hold any funds.</li>
			<li>ReDonate is a non-profit project. We have no interest or benefit in selling your data.</li>
		</ul>
		<div style="text-align: center; margin-top: 36px;">
			<form method="post" action="/sign-up">
				<button type="submit" class="green-button" id="button_subscribe">
					Sign up now!<br><span style="font-size: 12px;">(it's free, so why not?)</span>
				</button>
			</form>
		</div>
		<div style="font-size: 14px; margin-bottom: 24px; text-align: center;">
			(or <a href="/login">log in</a> to your existing account)
		</div>
	</div>
	<div class="clear"></div>
	<div class="featured">
		<h3 class="spaced">Who uses ReDonate?</h3>
		<div class="feature">
			<a href="http://cvm.cryto.net/">
				<img src="/static/images/promo/cvm.png">
			</a>
			<a href="/campaign/cvm" class="go">Go to campaign &raquo;</a>
		</div>
		<div class="feature">
			<a href="http://tahoe-lafs.org/">
				<img src="/static/images/promo/tahoe.png">
			</a>
			<a href="/campaign/tahoe-lafs" class="go">Go to campaign &raquo;</a>
		</div>
		<div class="feature">
			<a href="http://193.150.121.68:81/projects/fux/wiki">
				<img src="/static/images/promo/i2pfux.png">
			</a>
			<a href="/campaign/i2pfux" class="go">Go to campaign &raquo;</a>
		</div>
		<div class="feature">
			<a href="https://github.com/BlueVM/Neon">
				<img src="/static/images/promo/neon.png">
			</a>
			<a href="/campaign/neon-nginx-website-control-panel" class="go">Go to campaign &raquo;</a>
		</div>
		<div class="feature">
			<a href="http://anonyops.com/">
				<img src="/static/images/promo/anonyops.png">
			</a>
			<a href="/campaign/anonyops" class="go">Go to campaign &raquo;</a>
		</div>
		<div class="feature">
			<a href="http://id3nt.i2p.in/">
				<img src="/static/images/promo/id3nt.png">
			</a>
			<a href="/campaign/id3nt" class="go">Go to campaign &raquo;</a>
		</div>
		<div class="clear"></div>
	</div>
</div>
