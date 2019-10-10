
<!DOCTYPE html>
<html lang="{{ \App\Context::getContext()->language->iso_code }}">
<head>
<meta charset="utf-8">
</head>
<body>
<div>
	<br />
	Por favor, vaya al siguiente enlace para acceder:
	<br />
	<br />
	<a href="{{ route('salesrep.login') }}" target="_blank">{{ route('salesrep.login') }}</a>
	<br />
	<br />
	Utilice el email y la contraseña que indicó al registrarse. Si olvidó la contraseña, puede recuperarla en: 
	<br />
	<br />
	<a href="{{ route('salesrep.password.request') }}" target="_blank">{{ route('salesrep.password.request') }}</a>
	<br />
</div>
</body>
</html>