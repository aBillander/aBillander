

                  <h4>
                      <span style="color: #dd4814;">{{ l('Price Rules', 'pricerules') }}</span> &nbsp; 
                      [<a href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $product->reference }}</a>]
                      <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h4>

<div id="div_customer_rules">



   <div class="table-responsive">

@if ($customer_rules->count())
<table id="customer_rules" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{{l('ID', [], 'layouts')}}</th>
              <th>{{l('Rule Name', 'pricerules')}}</th>
    		      <th>{{l('Customer Group', 'pricerules')}}</th>
              <!-- th>{{l('Currency')}}</th -->
              <th class="text-right">{{l('Price')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" 
                                    data-content="{{ l('Prices shown: Rule Price (or Unit Price, if there are Extra Items), Unit Price (when applies, i.e. Price Rule is per Pack), Product Price (as seen on Product record).', 'pricerules') }}
                                    {{l('Price is WITHOUT Taxes.', 'pricerules')}}
@if( AbiConfiguration::isTrue('ENABLE_ECOTAXES') )
    <br />{{l('Prices are exclusive of Ecotax.', 'pricerules')}}
@endif
                  ">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
      <th> </th>
              <!-- th class="text-right">{{l('Discount Percent', 'pricerules')}}</th -->
              <!-- th class="text-right">{{l('Discount Amount', 'pricerules')}}</th -->
              <th class="text-center">{{l('From Quantity', 'pricerules')}}</th>
              <th class="text-center">{{l('Free Quantity', 'pricerules')}}</th>
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
      <td>{{ $rule->name }}
          <br /><span class="text-warning">[{{ \App\Models\PriceRule::getRuleTypeName($rule->rule_type) }}]</span> <span title="{{l('Creation date', 'pricerules')}}">{{ abi_date_short( $rule->created_at ) }}</span>
      </td>
      <!-- td>{{ optional($rule->category)->name }}</td -->
      <td>{{ optional($rule->customergroup)->name }}</td>
      <!-- td>{{ optional($rule->currency)->name }}</td -->

@if($rule->rule_type == 'promo')
      <td class="text-right">
                <span class="text-success">{{ $rule->as_priceable( ( $rule->from_quantity / ($rule->extra_quantity+$rule->from_quantity) ) * optional($rule->product)->price ) }}</span>

                <br /><span class="text-info crossed">{{ optional($rule->product)->as_price('price') }}</span>
      </td>
      <td></td>
@endif
@if($rule->rule_type == 'price')
      <td class="text-right">{{ $rule->as_price('price') }}

                <br /><span class="text-info crossed">{{ optional($rule->product)->as_price('price') }}</span>

      </td>
      <td></td>
@endif
@if($rule->rule_type == 'discount')
      <td class="text-right">{{ $rule->as_priceable( $rule->product->price * (1.0 - $rule->discount_percent/100.0 ) ) }}

                <br /><span class="text-success">-{{ $rule->as_percent('discount_percent') }} %</span>

                <br /><span class="text-info crossed">{{ optional($rule->product)->as_price('price') }}</span>

      </td>
      <td>%</td>
@endif
@if($rule->rule_type == 'pack')
      <td class="text-right">{{ $rule->as_price('price') }}

                <br /><span class="text-success">{{ $rule->as_priceable( $rule->price / $rule->conversion_rate ) }}</span>

                <br /><span class="text-info crossed">{{ optional($rule->product)->as_price('price') }}</span>

      </td>
      <td></td>
@endif

      <td class="text-center">{{ $rule->as_quantity('from_quantity') }}</td>

      <td class="text-center">{{ $rule->as_quantity('extra_quantity') ?: '' }}</td>

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


@else
{{--
            <div class="modal-footer">
                <a href="{{ URL::to('customers/'.$id.'/pricerules/create') }}" class="btn xbtn-sm btn-success create-pricerule pull-right" 
                title="{{l('Add New Item', [], 'layouts')}}" target="_blank"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

            </div>
--}}
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}


</div>
@endif


   </div><!-- div class="table-responsive" ENDS -->


</div><!-- div id="div_customer_rules" ENDS -->



