

<table class="head container">

    <tr>

        <td class="shop-info">

            <div class="shop-name">
                <h1 style="margin-top: 0mm; margin-bottom: 1mm;">Hoja de Producción :: {{ abi_date_form_short($sheet->due_date) }}</h1>
            </div>

            <div class="shop-name"><h3 style="font-weight: normal;color: #dd4814;"><strong>Resumen de Productos</strong></h3></div>
{{--
            <div class="shop-address" style="margin-top: 2mm;">
                        <strong>Fecha:</strong> {{ abi_date_form_short($sheet->due_date) }} <br>
                
            </div>
--}}
        </td>

        <td class="header">


        @if ($img = \App\Context::getContext()->company->company_logo)

            <img class="ximg-rounded" height="45" style="float:right" src="{{ URL::to( \App\Company::$company_path . $img ) }}" >

        @endif


        <div class="banner">
        </div>

        </td>

    </tr>

</table>



{{-- Rock n Roll here in. --}}



@if ( $sheet->customerorderlinesGrouped()->count() )

<div class="xtax-summary-wrapper print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 4mm;" xstyle="border: 1px #ccc solid">
      <tbody>

{{--
        <tr>
          <th width="14%">{{l('Product Reference')}}</th>
          <th width="50%">{{l('Product Name')}}</th>
          <th width="12%">{{l('Total')}}</th>
          <th width="12%">{{l('Measure')}}</th>
          <th width="12%">{{l('Per Unit')}}</th>
        </tr>
--}}

    <tr style="background-color: #f5f5f5;">
      <th>{{l('Product ID')}}</th>
      <th>{{l('Product Reference')}}</th>
      <th>{{l('Product Name')}}</th>
      <th>{{l('Quantity')}}</th>
    </tr>

  @foreach ($sheet->customerorderlinesGrouped() as $order)
    <tr>
      <td>{{ $order['product_id'] }}</td>
      <td>{{ $order['reference'] }}</td>
      <td>{{ $order['name'] }}</td>
      <td class="text-right" style="padding-right: 16px">{{ niceQuantity($order['quantity'], 0) }}</td>

    </tr>
  @endforeach

    </tbody>
</table>
</div><!-- div ENDS -->


@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif







<script type="text/php">

    if ( isset($pdf) ) {

        $pdf->page_text(30, ($pdf->get_height() - 26.89), "Impreso el: " . date('d M Y H:i:s'), null, 9);
        
        if ( 1 || $PAGE_COUNT > 1 )
        {
            $pdf->page_text(($pdf->get_width() - 82), ($pdf->get_height() - 26.89), "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9);

if ( $PAGE_NUM == 1 )
{
               // $pdf->page_text(($pdf->get_width() - 150), ($pdf->get_height() - 26.89 - 635.0), "{PAGE_NUM} de {PAGE_COUNT}", null, 9);
}
        }


    }

</script>
