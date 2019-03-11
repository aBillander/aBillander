
<div class="panel panel-success" id="panel_order_lines">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('') }}{{ l('Order Lines') }}</h3>
   </div>

   <div id="header_data" class="panel-collapse collapse in" aria-expanded="true" style="">


   <div class="panel-body">


{{-- --}}

<div id="div_order_lines">
          <div class="table-responsive">

@if ($order->lines->count())
    <table id="cart_lines" class="table table-hover">
        <thead>
            <tr>
              <th>{{ l('Reference') }}</th>
              <th colspan="2">{{ l('Product Name') }}</th>
               <th class="text-center button-pad">{{ l('Quantity') }}</th>
               <th class="text-right">{{ l('Customer Price') }}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                          data-trigger="hover" data-content="{{ l('Prices are exclusive of Tax') }}">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a></th>
               <th class="text-right">{{ l('Total') }}</th>
              <th class="text-right"> </th>
            </tr>
        </thead>
        <tbody class="sortable ui-sortable">
  @foreach ($order->lines as $line)
    <tr>
      <!-- td>{{ $line->id }}</td -->
      <td title="{{ optional($line->product)->id }}">@if (optional($line->product)->product_type == 'combinable') <span class="label label-info">{{ l('Combinations') }}</span>
                @else {{ $line->reference }}
                @endif</td>

      <td>
@php
  $img = optional($line->product)->getFeaturedImage();
@endphp
@if ($img)
              <a class="view-image" data-html="false" data-toggle="modal" 
                     href="{{ URL::to( \App\Image::$products_path . $img->getImageFolder() . $img->id . '-large_default' . '.' . $img->extension ) }}"
                     data-content="{{l('You are going to view a record. Are you sure?')}}" 
                     data-title="{{ l('Product Images') }} :: {{ $line->product->name }} " 
                     data-caption="({{$img->id}}) {{ $img->caption }} " 
                     onClick="return false;" title="{{l('View Image')}}">

                      <img src="{{ URL::to( \App\Image::$products_path . $img->getImageFolder() . $img->id . '-mini_default' . '.' . $img->extension ) . '?'. 'time='. time() }}" style="border: 1px solid #dddddd;">
              </a>
@endif
      </td>

      <td>{{ $line->name }}</td>

        <td>{{ $line->as_quantity('quantity') }}</td>

      <td class="text-right">{{ $line->as_price('unit_customer_final_price') }}</td>

      <td class="text-right">{{ $line->as_price('total_tax_excl') }}</td>

    </tr>
  @endforeach

{{-- Totals --}}
<tr class="info">
      <td></td>

      <td>
      </td>

      <td colspan="2"><h3>
            <span style="color: #dd4814;">{{ l('Total') }}</span>
        </h3></td>

        <td class="text-center lead"><h3>{{-- $cart->quantity --}}</h3></td>

      <td  class="text-center lead" colspan="3"><h3>{{ $order->as_price('total_tax_excl', $order->currency) }} {{ $order->currency->sign }}</h3></td>
</tr>

        </tbody>
    </table>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

       </div>

</div><!-- div id="div_order_lines" -->

{{-- --}}

    </div><!-- div class="panel-body" -->

               <!-- div class="panel-footer text-right">
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; Guardar
                  </button>
               </div -->

<!-- Order header ENDS -->

   </div>

   <div class="panel-footer text-right">       </div>

</div>


    

<!-- div id="panel_cart_total" class="">
  
    @ include('abcc.cart._panel_cart_total')

</div -->

@include('products._modal_view_image')


