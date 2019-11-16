
{{-- Totals --}}

<tr class="info">
    <td colspan="6"></td>

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

<tr class="info">
    <td colspan="6"></td>

    <td  colspan="2">
        <h4><span style="color: #dd4814;">{{ l('Shipping Cost') }}</span></h4>
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


{{-- Discounts --}}

@if( $cart->document_discount_percent != 0 )

<tr class="info">
    <td colspan="6"></td>

    <td  colspan="2">
            <h4>
                <span xstyle="color: #dd4814;">{{ l('Document Discount') }}: {{ $cart->as_percent('document_discount_percent') }}%</span>
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
        <h4>-{{ $cart->document_discount_amount_tax_excl }} {{ $cart->currency->sign }}</h4></td>
    </tr>
@endif

@if( $cart->document_ppd_percent != 0 )

<tr class="info">
    <td colspan="6"></td>

    <td  colspan="2">
            <h4>
                <span xstyle="color: #dd4814;">{{ l('Prompt Payment Discount') }}: {{ $cart->as_percent('document_ppd_percent') }}%</span>
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
        <h4>-{{ $cart->document_ppd_amount_tax_excl }} {{ $cart->currency->sign }}</h4></td>
    </tr>
@endif

@if( $cart->document_discount_percent != 0 || $cart->document_ppd_percent != 0 )

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

<tr class="info">
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
        <h4>{{ $cart->as_price('total_tax_incl') - $cart->as_price('total_tax_excl') - $cart->as_priceable($cart->total_se_tax) }}{{ $cart->currency->sign }}</h4>
    </td>
</tr>

    <tr class="info">
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

<tr class="info">
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
        <h4>{{ $cart->as_price('total_tax_incl') - $cart->as_price('total_tax_excl') }}{{ $cart->currency->sign }}</h4>
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
        <h3>{{ $cart->as_price('total_tax_incl') }}{{ $cart->currency->sign }}</h3>
    </td>
</tr>
