{{-- Totals --}}

<tr class="info">
    <td></td>
    <td></td>
    @if($config['show_taxes'])
        <td></td>
        <td></td>
    @endif

    <td colspan="2"><h3>
            <span style="color: #dd4814;">{{ l('Products Total') }}</span>
        </h3></td>

    <td class="text-center lead"><h3>{{ $cart->quantity }}</h3></td>

    <td class="text-center lead" colspan="3"><h3>{{ $cart->as_priceable($cart->amount) }} {{ $cart->currency->sign }}</h3></td>
</tr>

@php

    $dis1 = $cart->amount * $cart->customer->discount_percent/100.0;

    $dis2 = ($cart->amount - $dis1) * $cart->customer->discount_ppd_percent/100.0;

    $tot = ( $dis1 != 0 ) || ( $dis2 != 0 );

    $d1 = $cart->as_priceable($dis1);
    $d2 = $cart->as_priceable($dis2);

    $total = $cart->as_priceable($cart->amount) - $d1 - $d2;

@endphp

{{-- Dicounts --}}

@if( $d1 != 0 )
    <tr xclass="info">
        <td></td>

        <td>
        </td>

        <td colspan="2"><h4>
                <span xstyle="color: #dd4814;">{{ l('Document Discount') }}: {{ $cart->customer->as_percent('discount_percent') }}%</span>
            </h4></td>

        <td class="text-center lead"><h3></h3></td>

        <td class="text-center lead" colspan="3"><h4>-{{ $d1 }} {{ $cart->currency->sign }}</h4></td>
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

        <td class="text-center lead"><h3></h3></td>

        <td class="text-center lead" colspan="3"><h4>-{{ $d2 }} {{ $cart->currency->sign }}</h4></td>
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

        <td class="text-center lead"><h3></h3></td>

        <td class="text-center lead" colspan="3"><h3>{{ $cart->as_priceable($total) }} {{ $cart->currency->sign }}</h3></td>
    </tr>
@endif
