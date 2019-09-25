{{-- Totals --}}

<tr class="info">
    <td colspan="4"></td>

    <td>
        <h4><span style="color: #dd4814;">{{ l('Products Total') }}</span></h4>
    </td>

    <td class="text-center lead">
        <h4>{{ $cart->quantity }}</h4>
    </td>

    @if($config['display_with_taxes'])
        <td></td>
    @endif

    <td class="text-center lead" colspan="3">
        <h4>{{ $cart->as_priceable($cart->amount) }}{{ $cart->currency->sign }}</h4>
    </td>
</tr>

{{-- Discounts --}}

@if( $cart->discount1 != 0 )
    <tr xclass="info">
        <td colspan="4"></td>

        <td colspan="2">
            <h4>
                <span xstyle="color: #dd4814;">{{ l('Document Discount') }}: {{ $cart->customer->as_percent('discount_percent') }}%</span>
            </h4>
        </td>

        @if($config['display_with_taxes'])
            <td></td>
        @endif
        <td class="text-center lead" colspan="3"><h4>-{{ $cart->discount1 }} {{ $cart->currency->sign }}</h4></td>
    </tr>
@endif

@if( $cart->discount2 != 0 )
    <tr xclass="info">
        <td colspan="4"></td>

        <td colspan="2">
            <h4>
                <span xstyle="color: #dd4814;">{{ l('Prompt Payment Discount') }}: {{ $cart->customer->as_percent('discount_ppd_percent') }}%</span>
            </h4>
        </td>

        @if($config['display_with_taxes'])
            <td></td>
        @endif
        <td class="text-center lead" colspan="3"><h4>-{{ $cart->discount2 }} {{ $cart->currency->sign }}</h4></td>
    </tr>
@endif

@if( $cart->discounts_applied )
    <tr class="info">
        <td colspan="4"></td>

        <td colspan="2">
            <h4><span style="color: #dd4814;">{{ l('Total after discounts') }}</span></h4>
        </td>

        @if($config['display_with_taxes'])
            <td></td>
        @endif
        <td class="text-center lead" colspan="3"><h4>{{ $cart->as_priceable($cart->total_products) }} {{ $cart->currency->sign }}</h4></td>
    </tr>
@endif


<tr class="info">
    <td colspan="4"></td>
    <td colspan="2"><h4>{{ l('Taxes Total') }}</h4></td>
    @if($config['display_with_taxes'])
        <td></td>
    @endif
    <td class="text-center lead" colspan="3">
        <h4>{{$cart->total_taxes}}{{ $cart->currency->sign }}</h4>
    </td>
</tr>


<tr class="info">
    <td colspan="4"></td>
    <td colspan="2"><h3>{{ l('Order Total') }}</h3></td>

    @if($config['display_with_taxes'])
        <td></td>
    @endif
    <td class="text-center lead" colspan="3">
        <h3>{{$cart->total_price}}{{ $cart->currency->sign }}</h3>
    </td>
</tr>

