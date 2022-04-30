
<!DOCTYPE html>
<html lang="{{ AbiContext::getContext()->language->iso_code }}">
<head>
<meta charset="utf-8">
</head>
<body>
<div>
	<br />
	<br />
	<strong>Mensaje desde aBillander:</strong>
	<br />
	<br />
@if ($status == 'OK')
	La Copia de Seguridad de la Base de Datos se ha realizado correctamente. 
	<br />
	<br />
	{{ $message }}
@else
	La Copia de Seguridad de la Base de Datos ha fallado.
	<br />
	<br />
	{{ $message }}
@endif
	<br />
</div>
</body>
</html>