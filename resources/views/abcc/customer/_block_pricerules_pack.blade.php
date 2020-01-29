
@if ($customer_rules->where('rule_type', 'pack')->count())

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
              <th class="text-center">{{l('Price per Package')}}</th>
              <th class="text-center">{{l('Unit Price')}}</th>
              <th>{{l('Date from')}}</th>
              <th>{{l('Date to')}}</th>
            <th>  </th>
        </tr>
    </thead>
    <tbody id="pricerule_lines">

    @foreach ($customer_rules->where('rule_type', 'pack') as $rule)
        <tr>
      <td class="text-center">{{ $rule->id }}</td>
      <!-- td>{{ optional($rule->category)->name }}</td -->
      <td>
            @if($rule->product)
@php
  $img = $rule->product->getFeaturedImage();
@endphp
@if ($img)
              [<a class="view-image" data-html="false" data-toggle="modal" 
                       href="{{ URL::to( \App\Image::pathProducts() . $img->getImageFolder() . $img->id . '-large_default' . '.' . $img->extension ) }}"
                       data-content="{{ nl2p($rule->product->description_short) }} <br /> {{ nl2p($rule->product->description) }} " 
                       data-title="{{ l('Product Images') }} :: {{ $rule->product->name }} " 
                       data-caption="({{$img->id}}) {{ $img->caption }} " 
                       onClick="return false;" title="{{l('View Image', 'abcc/catalogue')}}">
  
                        {{ $rule->product->reference }}
                </a>]
  
                <img src="{{ URL::to( \App\Image::pathProducts() . $img->getImageFolder() . $img->id . '-mini_default' . '.' . $img->extension ) . '?'. 'time='. time() }}" style="border: 1px solid #dddddd;">

                {{ $rule->product->name }}
@else
              [<a class="view-image" data-html="false" data-toggle="modal" 
                       href=""
                       data-content="{{ nl2p($rule->product->description_short) }} <br /> {{ nl2p($rule->product->description) }} " 
                       data-title="{{ l('Product Images') }} :: {{ $rule->product->name }} " 
                       data-caption="" 
                       onClick="return false;" title="{{l('View', 'layouts')}}">
  
                        {{ $rule->product->reference }}
                </a>]

                {{ $rule->product->name }}
@endif
            @endif
      </td>
      <td class="text-center">{{ optional($rule->measureunit)->name }}<br />
      	<span class="text-danger">{{ $rule->as_quantity('conversion_rate') }}&nbsp;{{ optional(optional($rule->product)->measureunit)->name }}</span>
      </td>
      <!-- td>{{ optional($rule->currency)->name }}</td -->

@php

$regular_price = $rule->as_priceable(optional(optional($rule->product)->getPriceByCustomerPriceList( $customer, 1, $customer->currency ))->getPrice());

@endphp

      <td class="text-center">{{ $rule->as_price('price') }}</td>

      <td class="text-center">{{ $rule->as_priceable( $rule->price / $rule->conversion_rate ) }}<br /><span class="text-info crossed">{{ $regular_price }}</span></td>

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

