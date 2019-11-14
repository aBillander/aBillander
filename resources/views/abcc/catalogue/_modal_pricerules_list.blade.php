

	 <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h4 class="modal-title">{{ l('Show Special Prices') }} :: <label class="label label-default">{{ optional($product)->reference }}</label> {{ optional($product)->name }}</h4>
	 </div>

	 <div class="modal-body">

       <div class="table-responsive">

<table id="customer_rules" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{{l('ID', [], 'layouts')}}</th>
              <th>{{l('Category', 'abcc/customer')}}</th>
              <th>{{l('Currency', 'abcc/customer')}}</th>
              <th class="text-right">{{l('Price', 'abcc/customer')}}</th>
              <!-- th class="text-right">{{l('Discount Percent', 'abcc/customer')}}</th -->
              <!-- th class="text-right">{{l('Discount Amount')}}</th -->
              <th class="text-center">{{l('From Quantity', 'abcc/customer')}}</th>
              <th class="text-center">{{l('Extra Items', 'abcc/customer')}}</th>
              <th>{{l('Date from', 'abcc/customer')}}</th>
              <th>{{l('Date to', 'abcc/customer')}}</th>
        </tr>
    </thead>
	<tbody id="pricerule_lines">
@if ($customer_rules->count())


	@foreach ($customer_rules as $rule)
        <tr>

      <td class="text-center">{{ $rule->id }}</td>
      <td>{{ optional($rule->category)->name }}</td>
      <td>{{ optional($rule->currency)->name }}</td>

@if($rule->rule_type=='price')
      <td class="text-right">{{ $rule->as_price('price') }}</td>
@else
      <td class="text-right"> </td>
@endif

{{--
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
            <!-- td class="text-right"> </td -->
      @endif
@else
      <td class="text-right"> </td>
      <!-- td class="text-right"> </td -->
@endif
--}}

      <td class="text-center">{{ $rule->as_quantity('from_quantity') }}</td>

      <td class="text-center">{{ $rule->as_quantity('extra_quantity') ?: '' }}</td>

      <td>{{ abi_date_short( $rule->date_from ) }}</td>
            <td>{{ abi_date_short( $rule->date_to   ) }}</td>
        
        </tr>
	@endforeach

@else
    <tr><td colspan="10">
	<div class="alert alert-warning alert-block">
	    <i class="fa fa-warning"></i>
	    {{l('No records found', [], 'layouts')}}
	</div>
    </td>
    <td></td></tr>
@endif

	</tbody>
</table>



		</div>	 



	 </div><!-- div class="modal-body" ENDS -->

	<div class="modal-footer">

	   <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Back', [], 'layouts')}}</button>

	   <!-- button type="submit" class="btn btn-success" name="modal_edit_document_line_productSubmit" id="modal_edit_document_line_productSubmit">
	    <i class="fa fa-thumbs-up"></i>
	    &nbsp; {{l('Update', [], 'layouts')}}</button -->

	</div>
