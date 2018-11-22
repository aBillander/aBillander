
<!DOCTYPE html>
<html lang="{{ \App\Context::getContext()->language->iso_code }}">
<head>
<meta charset="utf-8">
</head>
<body>
		<h2>{{ $customer->name_fiscal }} se ha registrado en el Centro de Clientes de {{ \App\Context::getcontext()->company->name_fiscal }} y solicita permiso de acceso.</h2>
<div>
	<br />
	Su solicitud será estudiada, y recibirá un email con instrucciones.
	<br />
	<br />
	. 
	<br />
</div>
</body>
</html>