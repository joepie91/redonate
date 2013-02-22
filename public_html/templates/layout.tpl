<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href='http://fonts.googleapis.com/css?family=Lato:400,700|Roboto:500' rel='stylesheet' type='text/css'>
		<link href="/static/css/style.css" rel="stylesheet">
		<title>ReDonate :: {%?title}</title>
	</head>
	<body>
		<div class="wrapper">
			<div class="header">
				<h1>
					Re<span class="highlight">Donate</span>
				</h1>
			</div>
			<div class="main {%if padded == false}no-padding{%/if}">
				{%?contents}
			</div>
			<div class="footer">
				<a href="/">Home</a>
				<a href="/about">About</a>
				{%if logged-in == false}
					<a href="/sign-up">Sign up</a>
					<a href="/login">Login</a>
				{%else}
					<a href="/dashboard">Dashboard</a>
					<a href="/logout/{%?logout-key}">Logout</a>
				{%/if}
			</div>
		</div>
	</body>
</html>
