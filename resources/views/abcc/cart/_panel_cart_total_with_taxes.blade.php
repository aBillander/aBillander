
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


@if( 0 && $cart->document_discount_percent != 0 || $cart->document_ppd_percent != 0 )

<tr class="info">
    <td colspan="6"></td>

    <td  colspan="2">
            <h4><span style="color: #dd4814;">{{ l('Total after discounts') }}</span></h4>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h4>{{ $cart->as_priceable( $cart->total_products_tax_excl + $cart->$cart->total_shipping_tax_excl - $cart->document_discount_amount_tax_excl - $cart->document_ppd_amount_tax_excl ) }} {{ $cart->currency->sign }}</h4></td>
    </tr>
@endif

@if ($cart->customer->sales_equalization)

<tr class="active xinfo">
    <td colspan="6"></td>

    <td  colspan="2">
        <h4>{{ l('Taxes Total') }}</h4>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h4>{{ $cart->as_price('sub_tax_incl') - $cart->as_price('sub_tax_excl') - $cart->as_priceable($cart->total_se_tax) }}{{ $cart->currency->sign }}</h4>
    </td>
</tr>

    <tr class="active xinfo">
        <td colspan="6"></td>

        <td  colspan="2">
            <h4>{{ l('Sales Equalization Total') }}</h4>
        </td>

        <!-- td class="text-center lead">
            <h4>{{ $cart->quantity }}</h4>
        </td -->

        @if(0 && $config['display_with_taxes'])
            <td></td>
            <td></td>
        @endif

        <td class="text-center lead" colspan="3">
            <h4>{{ $cart->as_priceable($cart->total_se_tax) }}{{ $cart->currency->sign }}</h4>
        </td>
    </tr>
@else

<tr class="active xinfo">
    <td colspan="6"></td>

    <td  colspan="2">
        <h4>{{ l('Taxes Total') }}</h4>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h4>{{ $cart->as_price('sub_tax_incl') - $cart->as_price('sub_tax_excl') }}{{ $cart->currency->sign }}</h4>
    </td>
</tr>

@endif


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
        <h3>{{ $cart->as_price('sub_tax_incl') }}{{ $cart->currency->sign }}</h3>
    </td>
</tr>


{{-- Discounts --}}

@php

$document_discount = $cart->sub_tax_incl * $cart->customer->discount_percent / 100.0;

$document_ppd_discount = ($cart->sub_tax_incl-$document_discount) * $cart->customer->discount_ppd_percent / 100.0;

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
        <h3>{{ l('Total to Pay') }}</h3>
    </td>

    <!-- td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td -->

    @if(0 && $config['display_with_taxes'])
        <td></td>
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h3>{{ $cart->as_price('total_tax_incl') - 0*$cart->as_priceable( $document_discount ) - 0*$cart->as_priceable( $document_ppd_discount ) }}{{ $cart->currency->sign }}</h3>
    </td>
</tr>
@endif
