<!DOCTYPE html>
<html lang="{{ $iso_code }}">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	</head>
	<body>


    <div class="moz-forward-container">


      <div id="wrapper" dir="ltr" style="background-color: #f5f5f5;
        margin: 0; padding: 70px 0; width: 100%;
        -webkit-text-size-adjust: none;">
        <table width="100%" height="100%" cellspacing="0"
          cellpadding="0" border="0">
          <tbody>
            <tr>
              <td valign="top" align="center">
                <div id="template_header_image"> </div>
                <table id="template_container" style="background-color:
                  #fdfdfd; border: 1px solid #dcdcdc; box-shadow: 0 1px
                  4px rgba(0, 0, 0, 0.1); border-radius: 3px;"
                  width="600" cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                    <tr>
                      <td valign="top" align="center">
                        <!-- Header -->
                        <table id="template_header"
                          style="background-color: #e95420; color:
                          #ffffff; border-bottom: 0; font-weight: bold;
                          line-height: 100%; vertical-align: middle;
                          font-family: &quot;Helvetica Neue&quot;,
                          Helvetica, Roboto, Arial, sans-serif;
                          border-radius: 3px 3px 0 0;" width="100%"
                          cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td id="header_wrapper" style="padding:
                                36px 48px; display: block;">
                                <h1 style="font-family: &quot;Helvetica
                                  Neue&quot;, Helvetica, Roboto, Arial,
                                  sans-serif; font-size: 30px;
                                  font-weight: 300; line-height: 150%;
                                  margin: 0; text-align: left;
                                  text-shadow: 0 1px 0 #7797b4; color:
                                  #ffffff;">Nuevo Pedido de Cliente</h1>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <!-- End Header --> </td>
                    </tr>
                    <tr>
                      <td valign="top" align="center">
                        <!-- Body -->
                        <table id="template_body" width="600"
                          cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td id="body_content"
                                style="background-color: #fdfdfd;"
                                valign="top">
                                <!-- Content -->
                                <table width="100%" cellspacing="0"
                                  cellpadding="20" border="0">
                                  <tbody>
                                    <tr>
                                      <td style="padding: 48px 48px
                                        32px;" valign="top">
                                        <div id="body_content_inner"
                                          style="color: #737373;
                                          font-family: &quot;Helvetica
                                          Neue&quot;, Helvetica, Roboto,
                                          Arial, sans-serif; font-size:
                                          14px; line-height: 150%;
                                          text-align: left;">
                                          <p style="margin: 0 0 16px;">Ha
                                            recibido el siguiente pedido
                                            de <strong>{{ $customer->name_regular }}</strong>:</p>
                                          <h2 style="color: #e95420;
                                            display: block; font-family:
                                            &quot;Helvetica Neue&quot;,
                                            Helvetica, Roboto, Arial,
                                            sans-serif; font-size: 18px;
                                            font-weight: bold;
                                            line-height: 130%; margin: 0
                                            0 18px; text-align: left;">
                                            <a class="link"
href="{{ $url }}"
                                              style="font-weight:
                                              normal; text-decoration:
                                              underline; color:
                                              #e95420;"
                                              moz-do-not-send="true">Pedido
                                              {{ $document_num }}</a> ({{ $document_date }})</h2>
                                          <div style="margin-bottom:
                                            40px;">
                                            <table class="td"
                                              style="color: #737373;
                                              border: 1px solid #e4e4e4;
                                              vertical-align: middle;
                                              width: 100%; font-family:
                                              'Helvetica Neue',
                                              Helvetica, Roboto, Arial,
                                              sans-serif;"
                                              cellspacing="0"
                                              cellpadding="6" border="1">
                                              <thead> <tr>
                                                  <th class="td"
                                                    scope="col"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">Producto</th>
                                                  <th class="td"
                                                    scope="col"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">Cantidad</th>
                                                  <th class="td"
                                                    scope="col"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">Precio</th>
                                                </tr>
                                              </thead> <tbody>

@foreach ($document->documentlines->sortBy('line_sort_order') as $line)

			    @if ( 
			    			( $line->line_type != 'product' ) &&
			    			( $line->line_type != 'service' ) &&
			    			( $line->line_type != 'shipping' ) &&
			    			( $line->line_type != 'comment' )
			    )
			        @continue
			    @endif

@if( $line->line_type == 'comment' )
		<tr class="3655">
			<td class="sku first-column">
				<span>{{-- $line->reference --}}</span>
			</td>
			<td class="description" colspan="7">
				<span>
					<span class="item-name"><strong>{{ $line->name }}</strong></span>
					<span class="item-combination-options"></span>
				</span>
			</td>
		</tr>
@else
                                                <tr class="order_item">
                                                  <td class="td"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    padding: 12px;
                                                    text-align: left;
                                                    vertical-align:
                                                    middle; font-family:
                                                    'Helvetica Neue',
                                                    Helvetica, Roboto,
                                                    Arial, sans-serif;
                                                    word-wrap:
                                                    break-word;">
[{{ $line->reference }}] {{ $line->name }}
                                                  </td>
                                                  <td class="td"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    padding: 12px;
                                                    text-align: left;
                                                    vertical-align:
                                                    middle; font-family:
                                                    'Helvetica Neue',
                                                    Helvetica, Roboto,
                                                    Arial, sans-serif;">
{{ $line->as_quantity('quantity') }}
                                                  </td>
                                                  <td class="td"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    padding: 12px;
                                                    text-align: left;
                                                    vertical-align:
                                                    middle; font-family:
                                                    'Helvetica Neue',
                                                    Helvetica, Roboto,
                                                    Arial, sans-serif;">
{{ $line->as_price('total_tax_excl') }}
                                                </td>
                                                </tr>

@endif

@endforeach

                                              </tbody> <tfoot>
                                            	<tr style="display: none">
                                                  <th class="td"
                                                    scope="row"
                                                    colspan="2"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;
                                                    border-top-width:
                                                    4px;">Subtotal:</th>
                                                  <td class="td"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;
                                                    border-top-width:
                                                    4px;"><span
                                                      class="woocommerce-Price-amount
                                                      amount">39,92<span
class="woocommerce-Price-currencySymbol">€</span></span></td>
                                                </tr>
                                                <tr style="display: none">
                                                  <th class="td"
                                                    scope="row"
                                                    colspan="2"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">Envío:</th>
                                                  <td class="td"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">Envío
                                                    gratuito</td>
                                                </tr>
                                                <tr style="display: none">
                                                  <th class="td"
                                                    scope="row"
                                                    colspan="2"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">Método de
                                                    pago:</th>
                                                  <td class="td"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">Tarjeta</td>
                                                </tr>
                                                <tr>
                                                  <th class="td"
                                                    scope="row"
                                                    colspan="2"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">Total:</th>
                                                  <td class="td"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">
                                                    <span
                                                      class="woocommerce-Price-amount
                                                      amount"><strong>{{ \App\Models\Currency::viewMoneyWithSign($document_total, $document->currency) }}</strong></span> <small
                                                      class="includes_tax">(incluye Impuesto)</small>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <th class="td"
                                                    scope="row"
                                                    colspan="2"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">Nota:</th>
                                                  <td class="td"
                                                    style="color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;
                                                    vertical-align:
                                                    middle; padding:
                                                    12px; text-align:
                                                    left;">{{ $document->notes_from_customer }}
                                                </td>
                                                </tr>
                                              </tfoot>
                                            </table>
                                          </div>
                                          <table id="addresses"
                                            style="width: 100%;
                                            vertical-align: top;
                                            margin-bottom: 40px;
                                            padding: 0;" cellspacing="0"
                                            cellpadding="0" border="0">
                                            <tbody>
                                              <tr>
                                                <td style="text-align:
                                                  left; font-family:
                                                  'Helvetica Neue',
                                                  Helvetica, Roboto,
                                                  Arial, sans-serif;
                                                  border: 0; padding:
                                                  0;" width="50%"
                                                  valign="top">
                                                  <h2 style="color:
                                                    #e95420; display:
                                                    block; font-family:
                                                    &quot;Helvetica
                                                    Neue&quot;,
                                                    Helvetica, Roboto,
                                                    Arial, sans-serif;
                                                    font-size: 18px;
                                                    font-weight: bold;
                                                    line-height: 130%;
                                                    margin: 0 0 18px;
                                                    text-align: left;">Dirección
                                                    de Facturación</h2>
                                                  <address
                                                    class="address"
                                                    style="padding:
                                                    12px; color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;">

			@if ( $document->customer->name_commercial )

				{{ $document->customer->name_commercial }}

			@else

            	{{ $document->invoicingaddress->contact_name }}

			@endif

			<br />

            {{ $document->invoicingaddress->address1 }} {{ $document->invoicingaddress->address2 }}<br />

            {{ $document->invoicingaddress->postcode}} {{ $document->invoicingaddress->city }} <br />

            {{ $document->invoicingaddress->state->name }} <br />

                                                    <a
                                                      href="tel:626869844"
                                                      style="color:
                                                      #e95420;
                                                      font-weight:
                                                      normal;
                                                      text-decoration:
                                                      underline;"
                                                      moz-do-not-send="true">

			@if ( $document->invoicingaddress->phone )

				Tel. {{ $document->invoicingaddress->phone }}

			@else

				Tel. {{ $document->customer->phone }}

			@endif
                                                  	</a>
                                                    <br>
                                                    <a class="moz-txt-link-abbreviated" href="mailto:soo@movistar.es">
            {{ $document->invoicingaddress->email }}
        											</a> </address>
                                                </td>
                                                <td style="text-align:
                                                  left; font-family:
                                                  'Helvetica Neue',
                                                  Helvetica, Roboto,
                                                  Arial, sans-serif;
                                                  padding: 0;"
                                                  width="50%"
                                                  valign="top">
                                                  <h2 style="color:
                                                    #e95420; display:
                                                    block; font-family:
                                                    &quot;Helvetica
                                                    Neue&quot;,
                                                    Helvetica, Roboto,
                                                    Arial, sans-serif;
                                                    font-size: 18px;
                                                    font-weight: bold;
                                                    line-height: 130%;
                                                    margin: 0 0 18px;
                                                    text-align: left;">Dirección
                                                    de Envío</h2>
                                                  <address
                                                    class="address"
                                                    style="padding:
                                                    12px; color:
                                                    #737373; border: 1px
                                                    solid #e4e4e4;">


			@if ( $document->shippingaddress->name_commercial )

				{{ $document->shippingaddress->name_commercial }}

			@else

            	{{ $document->shippingaddress->contact_name }}

			@endif

			<br />

            {{ $document->shippingaddress->address1 }} {{ $document->shippingaddress->address2 }}<br />

            {{ $document->shippingaddress->postcode}} {{ $document->shippingaddress->city }} <br />

            {{ $document->shippingaddress->state->name }}

                                                    </address>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                          <p style="margin: 0 0 16px; display: none">Enhorabuena
                                            por la venta.</p>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <!-- End Content --> </td>
                            </tr>
                          </tbody>
                        </table>
                        <!-- End Body --> </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td valign="top" align="center">
                <!-- Footer -->
                <table id="template_footer" width="600" cellspacing="0"
                  cellpadding="10" border="0">
                  <tbody>
                    <tr>
                      <td style="padding: 0; border-radius: 6px;"
                        valign="top">
                        <table width="100%" cellspacing="0"
                          cellpadding="10" border="0">
                          <tbody>
                            <tr>
                              <td colspan="2" id="credit"
                                style="border-radius: 6px; border: 0;
                                color: #969696; font-family:
                                &quot;Helvetica Neue&quot;, Helvetica,
                                Roboto, Arial, sans-serif; font-size:
                                12px; line-height: 150%; text-align:
                                center; padding: 24px 0;"
                                valign="middle">
                                <p style="margin: 0 0 16px;">{{ $company->name_fiscal }}</p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- End Footer --> </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
















{{--
		<h2>Nuevo Pedido: {{ $document_num }} ({{ $document_date }})<br /> Total: {{ $document_total }}</h2>

		<div>
			<br /><!-- br />
			{{ $document_probe }}
			<br / --><br />
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
--}}
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
