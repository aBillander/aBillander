<!DOCTYPE html>
<html lang="{{ \App\Context::getContext()->language->iso_code }}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Nuevo Pedido: {{ $document_num }} ({{ $document_date }})<br /> Total: {{ $document_total }}</h2>

		<div>
			<br /><br />
			<hr />
			Cliente: {{ $customer->name_fiscal }}<br />
            NIF: {{ $customer->identification }}<br />
            {{ $customer->address->address1 }} {{ $customer->address->address2 }}<br />
            {{ $customer->address->postcode }} {{ $customer->address->city }}<br />
            {{ $customer->address->state->name }}, {{ $customer->address->country->name }}<br />
            Tel.: {{ $customer->address->phone }} / Email: {{ $customer->address->email }}<br />
            <hr />
		</div>

		<div>
			Puede ver el Pedido aquí: <a href="{{ $url }}">{{ $url }}</a>.
		</div>
{{--
		<div>
			Adjunto les enviamos la factura de referencia.<br /><br />
			{{ $custom_body }}<br /><br />
			Sin otro particular, reciban un cordial saludo.
		</div>

		<!-- div>
			To reset your password, complete this form: { { URL::to('password/reset', array($token)) }}.<br/>
			This link will expire in { { Config::get('auth.reminder.expire', 60) }} minutes.
		</div -->

		<div>
			<br /><br />
			<hr />
			{{ $company->name_fiscal }}<br />
            NIF: {{ $company->identification }}<br />
            {{ $company->address->address1 }} {{ $company->address->address2 }}<br />
            {{ $company->address->postcode }} {{ $company->address->city }}<br />
            {{ $company->address->state->name }}, {{ $company->address->country->name }}<br />
            Tel.: {{ $company->address->phone }} / Email: {{ $company->address->email }}<br />
            <hr />
		</div>
--}}
	</body>
</html>
