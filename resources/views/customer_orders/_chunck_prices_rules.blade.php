

                  <h4>
                      <span style="color: #dd4814;">{{ l('Price Rules', 'pricerules') }}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h4>

<div id="div_customer_rules">



   <div class="table-responsive">

@if ($customer_rules->count())
<table id="customer_rules" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{{l('ID', [], 'layouts')}}</th>
              <th>{{l('Category', 'pricerules')}}</th>
              <th colspan="1" class="text-center">{{l('Product')}}</th>
    		  <th>{{l('Customer Group', 'pricerules')}}</th>
              <th>{{l('Currency')}}</th>
              <th class="text-right">{{l('Price')}}</th>
              <th class="text-right">{{l('Discount Percent', 'pricerules')}}</th>
              <th class="text-right">{{l('Discount Amount', 'pricerules')}}</th>
              <th class="text-center">{{l('From Quantity', 'pricerules')}}</th>
              <th>{{l('Date from', 'pricerules')}}</th>
              <th>{{l('Date to', 'pricerules')}}</th>
            <!-- th>
                < ! - - a href="{ { URL::to('customers/'.$customer_id.'/pricerules/create') } }" class="btn btn-sm btn-success create-pricerule" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a - - >
            </th -->
        </tr>
    </thead>
    <tbody id="pricerule_lines">
        @foreach ($customer_rules as $rule)
        <tr>
      <td class="text-center">{{ $rule->id }}</td>
      <td>{{ optional($rule->category)->name }}</td>
      <td class="text-right">
          @if($rule->product)

            [<a href="{{ URL::to('products/' . optional($rule->product)->id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ optional($rule->product)->reference }}</a>]
          @else 
          	&nbsp;
          @endif
      </td>
      <td>{{ optional($rule->customergroup)->name }}</td>
      <td>{{ optional($rule->currency)->name }}</td>

@if($rule->rule_type=='price')
      <td class="text-right">{{ $rule->as_price('price') }}</td>
@else
      <td class="text-right"> </td>
@endif

@if($rule->rule_type=='discount')
      @if($rule->discount_type=='percentage')
            <td class="text-right">{{ $rule->as_percent('discount_percent') }}</td>
      @else
            <td class="text-right"> </td>
      @endif
      @if($rule->discount_type=='amount')
            <td class="text-right">{{ $rule->as_price('discount_amount') }} 
              ({{ $rule->discount_amount_is_tax_incl > 0 ? l('tax inc.') : l('tax exc.') }})
            </td>
      @else
            <td class="text-right"> </td>
      @endif
@else
      <td class="text-right"> </td>
      <td class="text-right"> </td>
@endif

      <td class="text-center">{{ $rule->as_quantity('from_quantity') }}</td>

      <td>{{ abi_date_short( $rule->date_from ) }}</td>
            <td>{{ abi_date_short( $rule->date_to   ) }}</td>



            <!-- td class="text-right button-pad">

                < ! - - a class="btn btn-sm btn-warning" href="{{ URL::to('pricerules/' . $rule->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a - - >

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('pricerules/' . $rule->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Price Rules') }} :: ({{$rule->id}}) " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
            
      </td -->
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->




@else
            <div class="modal-footer">
                <a href="{{ URL::to('customers/'.$id.'/pricerules/create') }}" class="btn xbtn-sm btn-success create-pricerule pull-right" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

            </div>

<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}


</div>
@endif

</div><!-- div id="div_customer_rules" ENDS -->



