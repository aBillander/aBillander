
@if ($customer_rules->where('rule_type', 'promo')->count())

    <h2>
        <span style="color: #cccccc;">/</span>
        <span style="color: #dd4814;">{{ l('Free Units Promotion') }}</span>
    </h2>


<div id="div_customer_rules">
   <div class="table-responsive">

<table id="customer_rules" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{{l('ID', [], 'layouts')}}</th>
              <th>{{l('Description')}}</th>
              <!-- th>{{l('Category')}}</th -->
              <th>{{l('Product')}}</th>
              <!-- th>{{l('Currency')}}</th -->
              <th class="text-center">{{l('Quantity')}}</th>
              <th class="text-center">{{l('Free Quantity')}}</th>
              <th class="text-center">{{l('Price')}}</th>
              <th>{{l('Date from')}}</th>
              <th>{{l('Date to')}}</th>
            <th>  </th>
        </tr>
    </thead>
    <tbody id="pricerule_lines">

    @foreach ($customer_rules->where('rule_type', 'promo') as $rule)
        <tr>
      <td class="text-center">{{ $rule->id }}</td>
      <td class="text-leftr">{{ $rule->name }}</td>
      <!-- td>{{ optional($rule->category)->name }}</td -->
      <td>
            <!-- a href="{{ URL::to('products/' . optional($rule->product)->name . '/edit') }}" title="{{l('View Product')}}" target="_blank" -->{{ optional($rule->product)->name }}<!-- /a -->
      </td>
      <!-- td>{{ optional($rule->currency)->name }}</td -->

      <td class="text-center">{{ $rule->as_quantity('from_quantity') }}</td>
      <td class="text-center">{{ $rule->as_quantity('extra_quantity') }}</td>

@php

$regular_price = $rule->as_priceable(optional(optional($rule->product)->getPriceByCustomerPriceList( $customer, 1, $customer->currency ))->getPrice());

$ratio = $rule->from_quantity / ($rule->from_quantity + $rule->extra_quantity);

@endphp

      <td class="text-center">{{ $rule->as_priceable( $regular_price * $ratio ) }}<br /><span class="text-info crossed">{{ $regular_price }}</span></td>

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

