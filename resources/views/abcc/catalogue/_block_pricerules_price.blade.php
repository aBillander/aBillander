
@if ($customer_rules->whereIn('rule_type', ['price', 'discount'])->count())

    <h2>
        <span style="color: #cccccc;">/</span>
        <span style="color: #dd4814;">{{ l('Price by Product', 'abcc/customer') }}</span>
    </h2>


<div id="div_customer_rules">
   <div class="table-responsive">

<table id="customer_rules" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{{l('ID', [], 'layouts')}}</th>
              <!-- th>{{l('Category', 'abcc/customer')}}</th -->
              <th>{{l('Product', 'abcc/customer')}}</th>
              <!-- th>{{l('Currency')}}</th -->
              <th class="text-right">{{l('Price', 'abcc/customer')}}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" data-container="body" 
                          data-content="{{ l('Prices are exclusive of Tax', 'abcc/catalogue') }}
@if( \App\Configuration::isTrue('ENABLE_ECOTAXES') )
    <br />
    {!! l('Prices are inclusive of Ecotax', 'abcc/catalogue') !!}
@endif
                  ">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a>
              </th>
              <th class="text-center">{{l('From Quantity', 'abcc/customer')}}</th>
              <th>{{l('Date from', 'abcc/customer')}}</th>
              <th>{{l('Date to', 'abcc/customer')}}</th>
            <th>  </th>
        </tr>
    </thead>
    <tbody id="pricerule_lines">

    @foreach ($customer_rules->whereIn('rule_type', ['price', 'discount']) as $rule)
        <tr>
      <td class="text-center">{{ $rule->id }}</td>
      <!-- td>{{ optional($rule->category)->name }}</td -->
      <td>
            <!-- a href="{{ URL::to('products/' . optional($rule->product)->name . '/edit') }}" title="{{l('View Product')}}" target="_blank" -->{{ optional($rule->product)->name }}<!-- /a -->
      </td>
      <!-- td>{{ optional($rule->currency)->name }}</td -->
@php
    $priceListPrice = optional(optional($rule->product)->getPriceByCustomerPriceList( $customer, 1, $customer->currency ))->getPrice();
@endphp

@if($rule->rule_type=='price')
      <td class="text-right">
        {{ $rule->as_priceable( $rule->getUnitPrice( $customer->currency ) ) }}
        <br /><span class="text-info crossed">{{ $rule->as_priceable( $priceListPrice ) }}</span></td>

@elseif($rule->rule_type=='discount')
      <td class="text-right">
        {{ $rule->as_priceable( $rule->getUnitPrice( $customer->currency ) ) }}
        <br /><span class="text-success">-{{ $rule->as_percent('discount_percent') }} %</span>
        <br /><span class="text-info crossed">{{ $rule->as_priceable( $priceListPrice ) }}</span></td>

@else
      <td class="text-right"> </td>
@endif
      <td class="text-center">{{ $rule->as_quantity('from_quantity') }}</td>

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

