
<!DOCTYPE html>
<html lang="{{ \App\Context::getContext()->language->iso_code }}">
<head>
<meta charset="utf-8">
</head>
<body>
		<h2>{{ $customer->name_fiscal }} se ha registrado en el Centro de Clientes y solicita permiso de acceso.</h2>
<div>
	<br />
	<strong>Tarea:</strong> <a href="{{ route('todos.edit', [$todo->id]) }}" target="_blank">{{ route('todos.edit', [$todo->id]) }}</a>
	<br />
	<br />
	Por favor, vaya al siguiente enlace:
	<br />
	<br />
	<a href="{{ $todo->url }}" target="_blank">{{ $todo->url }}</a>
	<br />
	<br />
	. 
	<br />
</div>
</body>
</html>