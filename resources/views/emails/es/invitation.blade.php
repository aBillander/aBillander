
<!DOCTYPE html>
<html lang="{{ AbiContext::getContext()->language->iso_code }}">
<head>
<meta charset="utf-8">
</head>
<body>
<div>
	<br />
	<strong>Responder a:</strong> [{!! $user_name !!}] :: {!! $user_email !!}
	<br />
	<br />
	{!! $user_message !!}
	<br />
	<br />
	Por favor, vaya al siguiente enlace para registrarse:
	<br />
	<br />
	<a href="{{ route('customer.register') }}" target="_blank">{{ route('customer.register') }}</a>
	<br />
	<br />
	Después de realizar el registro, recibirá un correo electrónico para confirmar la activación de su Cuenta con nosotros. 
	<br />
</div>
</body>
</html>