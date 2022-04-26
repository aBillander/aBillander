
@if ($pricerules_pack->count())

    <h2>
        <span style="color: #cccccc;">/</span>
        <span style="color: #dd4814;">{{ l('Price by Package') }}</span>
    </h2>


<div id="div_customer_rules">
   <div class="table-responsive">

<table id="customer_rules" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{{l('ID', [], 'layouts')}}</th>
              <!-- th>{{l('Category')}}</th -->
              <th>{{l('Product')}}</th>
              <th class="text-center">{{l('Package')}}</th>
              <!-- th>{{l('Currency')}}</th -->
              <!-- th class="text-center">{{l('From Quantity')}}</th -->
              <th class="text-center">{{l('Price per Package')}}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" data-container="body" 
                          data-content="{{ l('Prices are exclusive of Tax', 'abcc/catalogue') }}
@if( AbiConfiguration::isTrue('ENABLE_ECOTAXES') )
    <br />
    {!! l('Prices are inclusive of Ecotax', 'abcc/catalogue') !!}
@endif
                  ">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a></span>
              </th>
              <th class="text-center">{{l('Unit Price')}}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" data-container="body" 
                          data-content="{{ l('Prices are exclusive of Tax', 'abcc/catalogue') }}
@if( AbiConfiguration::isTrue('ENABLE_ECOTAXES') )
    <br />
    {!! l('Prices are inclusive of Ecotax', 'abcc/catalogue') !!}
@endif
                  ">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a>
              </th>
              <th>{{l('Date from')}}</th>
              <th>{{l('Date to')}}</th>
            <th>  </th>
        </tr>
    </thead>
    <tbody id="pricerule_lines">

    @foreach ($pricerules_pack as $rule)
        <tr>
      <td class="text-center">{{ $rule->id }}</td>
      <!-- td>{{ optional($rule->category)->name }}</td -->
      <td>
            @if($rule->product)
@php
  $img = $rule->product->getFeaturedImage();
@endphp

              [<a class="view-image-multiple" data-html="false" data-toggle="modal" 
                       href="{{ URL::to( \App\Models\Image::pathProducts() . $img->getImageFolder() . $img->filename . '-large_default' . '.' . $img->extension ) }}"
                       data-content="{{ nl2p($rule->product->description_short) }} <br /> {{ nl2p($rule->product->description) }} " 
                       data-title="{{ l('Product Images') }} :: {{ $rule->product->name }} " 
                       data-caption="({{$img->filename}}) {{ $img->caption }} " 
                       data-id="{{ $rule->product->id }}" 
                       onClick="return false;" title="{{l('View Image', 'abcc/catalogue')}}">
  
                        {{ $rule->product->reference }}
                </a>]
  
                <img src="{{ URL::to( \App\Models\Image::pathProducts() . $img->getImageFolder() . $img->filename . '-mini_default' . '.' . $img->extension ) . '?'. 'time='. time() }}" style="border: 1px solid #dddddd;">

                {{ $rule->product->name }}
            @endif
      </td>
      <td class="text-center">{{ optional($rule->measureunit)->name }}<br />
      	<span class="text-danger">{{ $rule->as_quantity('conversion_rate') }}&nbsp;{{ optional(optional($rule->product)->measureunit)->name }}</span>
      </td>
      <!-- td>{{ optional($rule->currency)->name }}</td -->

@php

$priceListPrice = $rule->as_priceable(optional(optional($rule->product)->getPriceByCustomerPriceList( $customer, 1, $customer->currency ))->getPrice());

$priceRule = $rule->getUnitPrice( $customer->currency );

@endphp

      <td class="text-center">{{ $rule->as_priceable($priceRule * $rule->conversion_rate) }}</td>

      <td class="text-center">{{ $rule->as_priceable( $priceRule ) }}<br /><span class="text-info crossed">{{ $priceListPrice }}</span></td>

      <td>{{ abi_date_short( $rule->date_from ) }}</td>
            <td>{{ abi_date_short( $rule->date_to   ) }}</td>



            <td class="text-right button-pad">

{{--
                <!-- a class="btn btn-sm btn-warning" href="{{ URL::to('pricerules/' . $rule->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('pricerules/' . $rule->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Price Rules') }} :: ({{$rule->id}}) " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
--}}
      </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->


</div><!-- div id="div_customer_rules" ENDS -->


@endif

