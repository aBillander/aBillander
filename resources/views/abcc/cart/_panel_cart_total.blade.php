
{{-- Totals --}}

<tr class="warning xinfo">
    <td colspan="6" style="width: 50%"></td>

    <td  colspan="2">
        <h4><span style="color: #dd4814;">{{ l('Products Total') }}</span></h4>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h4>{{ $cart->as_price('total_products_tax_excl') }}{{ $cart->currency->sign }}</h4>
    </td>
</tr>


{{-- Shipping --}}

<tr class="warning xinfo">
    <td colspan="6"></td>

    <td  colspan="2">
        <h4 title="{{ optional( $cart->cartshippingline() )->name }}"><span style="color: #dd4814;">{{ l('Shipping Cost') }}</span>

                        @if ( $cart->total_shipping_tax_excl > 0.0 && \App\Configuration::get('ABCC_FREE_SHIPPING_PRICE') > 0.0 )
                               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                                  xdata-trigger="focus"
                                  data-html="true" 
                                  data-content="{!! l('Free Shipping for Orders greater than: :amount (Products Value)',
                                                ['amount' => abi_money( \App\Configuration::get('ABCC_FREE_SHIPPING_PRICE'), $cart->currency )] ) !!}
                                    ">
                                  <i class="fa fa-question-circle abi-help" style="color: #ff0084;"></i>
                               </a>
                        @endif
        </h4>
        {{ optional($cart->shippingaddress->getShippingMethod())->name }}
        @if ( optional($cart->shippingaddress->getShippingMethod())->carrier )
            :: {{ $cart->shippingaddress->getShippingMethod()->carrier->name}}
        @endif
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h4>{{ $cart->as_price('total_shipping_tax_excl') }}{{ $cart->currency->sign }}</h4>
    </td>
</tr>

{{-- Order Total without taxes --}}

<tr class="info">
    <td colspan="6"></td>

    <td  colspan="2">
        <h3>{{ l('Order Total') }}</h3>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h3>{{ $cart->as_price('sub_tax_excl') }}{{ $cart->currency->sign }}</h3>
    </td>
</tr>


{{-- Discounts --}}

@php

$document_discount = $cart->sub_tax_excl * $cart->customer->discount_percent / 100.0;

$document_ppd_discount = ($cart->sub_tax_excl-$document_discount) * $cart->customer->discount_ppd_percent / 100.0;

$has_discount = $document_discount != 0.0 || $document_ppd_discount != 0.0;

@endphp

@if( $document_discount != 0 )

<tr class="active xinfo">
    <td colspan="6"></td>

    <td  colspan="2">
            <h4>
                <span xstyle="color: #dd4814;">{{ l('Document Discount') }}: {{ $cart->as_percentable($cart->customer->discount_percent) }}%</span>
            </h4>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h4>-{{ $cart->as_priceable( $document_discount ) }} {{ $cart->currency->sign }}</h4></td>
    </tr>
@endif

@if( $document_ppd_discount != 0 )

<tr class="active xinfo">
    <td colspan="6"></td>

    <td  colspan="2">
            <h4>
                <span xstyle="color: #dd4814;">{{ l('Prompt Payment Discount') }}: {{ $cart->as_percentable($cart->customer->discount_ppd_percent) }}%</span>
            </h4>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h4>-{{ $cart->as_priceable( $document_ppd_discount ) }} {{ $cart->currency->sign }}</h4></td>
    </tr>
@endif


@if($has_discount)

<tr class="info">
    <td colspan="6"></td>

    <td  colspan="2">
        <h3>{{ l('Total') }}</h3>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h3>{{ $cart->as_price('total_tax_excl') - 0*$cart->as_priceable( $document_discount ) - 0*$cart->as_priceable( $document_ppd_discount ) }}{{ $cart->currency->sign }}</h3>
    </td>
</tr>
@endif


<tr class="active">
    <td colspan="6"></td>

    <td  colspan="2">
        <h3>{{ l('Total (Tax included)') }}</h3>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h3>{{ $cart->as_price('total_tax_incl') }}{{ $cart->currency->sign }}</h3>
    </td>
</tr>




{{-- --} }
<tr class="info">
      <td></td>

      <td>
      </td>

      <td colspan="2"><h3>
            <span style="color: #dd4814;">{{ l('Products Total') }}</span>
        </h3></td>

        <td class="text-center lead"><h3>{{ $cart->quantity }}</h3></td>

      <td  class="text-center lead" colspan="3"><h3>{{ $cart->as_priceable($cart->amount) }} {{ $cart->currency->sign }}</h3></td>
</tr>

@php

$dis1 = $cart->amount * $cart->customer->discount_percent/100.0;

$dis2 = ($cart->amount - $dis1) * $cart->customer->discount_ppd_percent/100.0;

$tot = ( $dis1 != 0 ) || ( $dis2 != 0 );

$d1 = $cart->as_priceable($dis1);
$d2 = $cart->as_priceable($dis2);

$total = $cart->as_priceable($cart->amount) - $d1 - $d2;

@endphp

{{-- Dicounts --} }

@if( $d1 != 0 )
<tr xclass="info">
      <td></td>

      <td>
      </td>

      <td colspan="2"><h4>
            <span xstyle="color: #dd4814;">{{ l('Document Discount') }}: {{ $cart->customer->as_percent('discount_percent') }}%</span>
        </h4></td>

        <td class="text-center lead"><h3> </h3></td>

      <td  class="text-center lead" colspan="3"><h4>-{{ $d1 }} {{ $cart->currency->sign }}</h4></td>
</tr>
@endif

@if( $d2 != 0 )
<tr xclass="info">
      <td></td>

      <td>
      </td>

      <td colspan="2"><h4>
            <span xstyle="color: #dd4814;">{{ l('Prompt Payment Discount') }}: {{ $cart->customer->as_percent('discount_ppd_percent') }}%</span>
        </h4></td>

        <td class="text-center lead"><h3> </h3></td>

      <td  class="text-center lead" colspan="3"><h4>-{{ $d2 }} {{ $cart->currency->sign }}</h4></td>
</tr>
@endif

@if( $tot )
<tr class="info">
      <td></td>

      <td>
      </td>

      <td colspan="2"><h3>
            <span style="color: #dd4814;">{{ l('Total') }}</span>
        </h3></td>

        <td class="text-center lead"><h3> </h3></td>

      <td  class="text-center lead" colspan="3"><h3>{{ $cart->as_priceable($total) }} {{ $cart->currency->sign }}</h3></td>
</tr>
@endif

{ {-- --}}