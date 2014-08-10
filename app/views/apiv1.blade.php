<!doctype html>
<html lang="en">
<head>
    <title>Turt2Live UUID</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

</head>
<body>
<div style="text-align: center; width: 100%">
	<h1>Turt2Live UUID Service</h1>
    <h3 style='color:red'>This API is deprecated. Please click <a href="{{ URL::route('home') }}">here</a> for the updated API.</h3>
    <b>Endpoints (GET):</b><br/>
	<code>http://uuid.turt2live.com/uuid/&lt;player name&gt;</code> <a href='uuid/turt2live' target='_BLANK'>Show Me</a><br/>
	<code>http://uuid.turt2live.com/name/&lt;player UUID&gt;</code> <a href='name/c465b1543c294dbfa7e3e0869504b8d8' target='_BLANK'>Show Me</a><br/>
	<code>http://uuid.turt2live.com/uuid/list/&lt;player name&gt;;&lt;player name&gt;;&lt;...&gt;</code> <a href='uuid/list/turt2live;drtshock' target='_BLANK'>Show Me</a><br/>
	<code>http://uuid.turt2live.com/name/list/&lt;player UUID&gt;;&lt;player UUID&gt;;&lt;...&gt;</code> <a href='name/list/c465b1543c294dbfa7e3e0869504b8d8;7afc5cdfaebd43e5ac7c9c7e48243c6a' target='_BLANK'>Show Me</a><br/>
	<code>http://uuid.turt2live.com/history/&lt;player UUID&gt;</code> <a href='history/c465b1543c294dbfa7e3e0869504b8d8' target='_BLANK'>Show Me</a><br/>
    <code>http://uuid.turt2live.com/random/&lt;amount&gt;</code> <a href='random/25' target='_BLANK'>Show Me</a><br/>
    <br/>
    <b>Endpoints (POST):</b><br/>
	<code>http://uuid.turt2live.com/uuid</code> - Sample JSON: <code>{"names": ["turt2live"]}</code><br/>
	<code>http://uuid.turt2live.com/name</code> - Sample JSON: <code>{"uuids": ["c465b1543c294dbfa7e3e0869504b8d8"]}</code><br/>
    <br/>
    <span style="color: #ABABAB; font-size: 0.8em">&copy; 2014 Travis Ralston. This service is not affiliated with Mojang AB or their partners</span><br/>
    <span style="color: #ABABAB; font-size: 0.8em">Please contact Travis Ralston (<a href="mailto:travis@turt2live.com">travis@turt2live.com</a>) for any questions or concerns</span><br/>
    <span style="color: #ABABAB; font-size: 0.8em">This service is provided free of charge</span><br/>
</div>
</body>
</html>
