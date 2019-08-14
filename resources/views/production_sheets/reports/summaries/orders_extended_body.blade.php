

<table class="head container">

    <tr>

        <td class="shop-info">

            <div class="shop-name">
                <h1 style="margin-top: 0mm; margin-bottom: 1mm;">Hoja de Producción :: {{ abi_date_form_short($sheet->due_date) }}</h1>
            </div>

            <div class="shop-name"><h3 style="font-weight: normal;color: #dd4814;"><strong>Resumen de Pedidos</strong></h3></div>
{{--
            <div class="shop-address" style="margin-top: 2mm;">
                        <strong>Fecha:</strong> {{ abi_date_form_short($sheet->due_date) }} <br>
                
            </div>
--}}
        </td>

        <td class="header">


        @if ($img = \App\Context::getContext()->company->company_logo)

            <img class="ximg-rounded" height="45" style="float:right" src="{{ URL::to( \App\Company::imagesPath() . $img ) }}" >

        @endif


        <div class="banner">
        </div>

        </td>

    </tr>

</table>



{{-- Rock n Roll here in. --}}



@if ( $sheet->customerorders()->count() )

<table class="order-details tax-summary xprint-friendly" style="margin-bottom: 4mm;" xstyle="border: 1px #ccc solid">
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
      <th>{{l('ID', [], 'layouts')}}</th>
      <th>{{l('Customer External Reference')}}</th>
      <th>{{l('Date')}}</th>
      <th>{{l('Customer')}}</th>
      <th class="text-left">{{ l('Deliver to') }}</th>
      <th class="text-left">{{l('Reference')}}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
      <th>{{l('Shipping Method')}}</th>
    </tr>

  @foreach ($sheet->customerorders as $order)
    <tr>
      <td><strong>{{ $order->document_reference ?: $order->id }}</strong></td>
      <td><strong>{{ $order->customer->reference_external }}</strong></td>
      <td><strong>{{ abi_toLocale_date_short($order->created_at) }}</strong></td>
      <td><strong>{!! $order->customerInfo() !!}</strong>
                 <!-- a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->customerCardFull() }}">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a -->
      </td>
      <td>
          @if ( $order->hasShippingAddress() )



          {{ $order->shippingaddress->alias }} 
           <!-- a href="javascript:void(0);">
              <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" data-content="{{ $order->shippingaddress->firstname }} {{ $order->shippingaddress->lastname }}<br />{{ $order->shippingaddress->address1 }}<br />{{ $order->shippingaddress->city }} - {{ $order->shippingaddress->state->name }} <a href=&quot;javascript:void(0)&quot; class=&quot;btn btn-grey btn-xs disabled&quot;>{{ $order->shippingaddress->phone }}</a>" data-original-title="" title="">
                  <i class="fa fa-address-card-o"></i>
              </button>
           </a -->


          @endif
      </td>
      <td>{{ $order->reference }}</td>
      <td class="text-center">
                @if ($order->notes_from_customer && 0)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->notes_from_customer }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif

                @if ($order->all_notes && 0)
                 <a href="javascript:void(0);">
                    <button type="button" style="padding: 3px 8px;
font-size: 12px;
line-height: 1.5;
border: 1px solid #adadad;;
border-radius: 3px;" xclass="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($order->all_notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
                {!! nl2br($order->all_notes) !!}
      </td>
      <td title="{{ $order->carrier->name ?? '' }}">{{ $order->shippingmethod->name ?? '' }}</td>

    </tr>

    {{-- Order Lines. Not Gorrino Way! --}}


  @foreach ($order->lines as $line)
    <tr>
      <!-- td>{ { $item['product_id'] } }</td -->
      <td xwidth="18%"> &nbsp; </td>
      <td xwidth="10%">{{ $line->reference }}</td>
      <td xwidth="40%" colspan="3">{{ $line->name }}</td>
      <td xwidth="5%" class="text-right" style="padding-right: 16px">{{ niceQuantity($line->quantity, 0) }}</td>
      <td xwidth="27%">{{ $line->measureunit->name }}</td>
      <td xwidth="18%"> &nbsp; </td>

    </tr>


  @endforeach


  @endforeach

    </tbody>
</table>


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
