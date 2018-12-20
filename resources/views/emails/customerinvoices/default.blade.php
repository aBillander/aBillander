<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Su Factura: {{ $invoice_num }} ({{ $invoice_date }})<br /> Total: {{ $invoice_total }}</h2>

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
	</body>
</html>
