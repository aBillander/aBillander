<div id="panel_document"> 

<div class="panel with-nav-tabs panel-info">

   <div class="panel-heading">

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab">{{ l('New Price Rule') }}</a></li>
                            <li><a href="#tab2default" data-toggle="tab">{{ l('New Quantity Rule') }}</a></li>
                        </ul>

   </div>


				{!! Form::open(array('route' => 'pricerules.store')) !!}


	<div class="panel-body">

      {{-- Common --}}



                @include('errors.list')


<div class="row">
    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('date_from') ? 'has-error' : '' }}">
        {!! Form::label('date_from_form', l('Date from')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
               {!! $errors->first('date_from', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('date_to') ? 'has-error' : '' }}">
        {!! Form::label('date_to_form', l('Date to')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
               {!! $errors->first('date_to', '<span class="help-block">:message</span>') !!}
    </div>
	<div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
	    {!! Form::label('name', l('Rule Name')) !!}
	    {!! Form::text('name', null, array('class' => 'form-control')) !!}
               {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
	</div>
</div>

    
<div class="row">
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('autocustomer_name', l('Customer Name')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('autocustomer_name', old('autocustomer_name'), array('class' => 'form-control', 'id' => 'autocustomer_name', 'onclick' => 'this.select()')) !!}

        {!! Form::hidden('customer_id', old('customer_id'), array('id' => 'customer_id')) !!}

        {!! $errors->first('customer_id', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('customer_group_id', l('Customer Group')) !!}
        {!! Form::select('customer_group_id', ['' => l('-- All --', 'layouts')] + $customer_groupList, null, array('class' => 'form-control', 'id' => 'customer_group_id')) !!}
        {!! $errors->first('customer_group_id', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('reference', l('Reference')) !!}
        {!! Form::text('reference', null, array('id' => 'reference', 'class' => 'form-control', 'onfocus' => 'this.blur()')) !!}
    </div>
    <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('product_query', l('Product Name')) !!}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                          data-content="{{ l('Search by Product Reference or Name') }}">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a>

        {!! Form::hidden('product_id', null, array('id' => 'product_id')) !!}

        {!! Form::text('product_query', null, array('id' => 'product_query', 'autocomplete' => 'off', 'class' => 'form-control', 'onclick' => 'this.select()')) !!}
    </div>

</div>


  		<div class="tab-content">

		      <div class="tab-pane fade in active" id="tab1default">
		                
		                @include('price_rules._tab_create_rule_price')

		      </div>
		      <div class="tab-pane fade" id="tab2default">
		                
		                @include('price_rules._tab_create_rule_promo')

		      </div>

    	</div>

	</div><!-- div class="panel-body" -->
{{--
      <!-- Common Footer -->

               <div class="panel-footer text-right">
                  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('pricerules') }}">{{l('Cancel', [], 'layouts')}}</a>
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;sanitize_data();this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>
--}}

				{!! Form::hidden('rule_type', 'price', array('id' => 'rule_type')) !!}

				{!! Form::close() !!}

</div>    <!-- div class="panel panel-info" ENDS -->

</div>



@section('styles') @parent

    @include('customer_orders.css.panels')

@endsection
