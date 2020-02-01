
@if ($customer_rules->where('rule_type', 'pack')->count())

    <h2>
        <span style="color: #cccccc;">/</span>
        <span style="color: #dd4814;">{{ l('Price by Package', 'abcc/customer') }}</span>
    </h2>


<div id="div_customer_rules">
   <div class="table-responsive">

<table id="customer_rules" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{{l('ID', [], 'layouts')}}</th>
              <!-- th>{{l('Category', 'abcc/customer')}}</th -->
              <th>{{l('Product', 'abcc/customer')}}</th>
              <th class="text-center">{{l('Package', 'abcc/customer')}}</th>
              <!-- th>{{l('Currency')}}</th -->
              <!-- th class="text-center">{{l('From Quantity')}}</th -->
              <th class="text-center">{{l('Price per Package', 'abcc/customer')}}</th>
              <th class="text-center">{{l('Unit Price', 'abcc/customer')}}</th>
              <th>{{l('Date from', 'abcc/customer')}}</th>
              <th>{{l('Date to', 'abcc/customer')}}</th>
            <th>  </th>
        </tr>
    </thead>
    <tbody id="pricerule_lines">

    @foreach ($customer_rules->where('rule_type', 'pack') as $rule)
        <tr>
      <td class="text-center">{{ $rule->id }}</td>
      <!-- td>{{ optional($rule->category)->name }}</td -->
      <td>
            <!-- a href="{{ URL::to('products/' . optional($rule->product)->name . '/edit') }}" title="{{l('View Product')}}" target="_blank" -->{{ optional($rule->product)->name }}<!-- /a -->
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

