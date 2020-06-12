
<!DOCTYPE html>
<html lang="{{ \App\Context::getContext()->language->iso_code }}">
<head>
<meta charset="utf-8">
</head>
<body>
<div>
	<br />
	<strong>Responder a:</strong> [{!! $user_name !!}] :: {!! $user_email !!}
	<br />
	{!! $url !!}
	<br />
	<br />
	{!! $user_message !!}
</div>
</body>
</html>