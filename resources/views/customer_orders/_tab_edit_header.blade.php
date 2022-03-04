
    {!! Form::model($document, array('method' => 'PATCH', 'route' => array($model_path.'.update', $document->id), 'id' => 'update_'.$model_snake_case, 'name' => 'update_'.$model_snake_case, 'class' => 'form')) !!}


<!-- Order header -->

{!! Form::hidden('document_id', $document->id, array('id' => 'document_id')) !!}
{!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}
          {!! Form::hidden('customer_price_is_tax_inc', $customer->currentPricesEnteredWithTax( $document->document_currency ), array('id' => 'customer_price_is_tax_inc')) !!}
{!! Form::hidden('sales_equalization', $customer->sales_equalization, array('id' => 'sales_equalization')) !!}
{!! Form::hidden('invoicing_address_id', null, array('id' => 'invoicing_address_id')) !!}
{!! Form::hidden('taxing_address_id', $document->taxingaddress->id, array('id' => 'taxing_address_id')) !!}

          {!! Form::hidden('currency_decimalPlaces', $document->currency->decimalPlaces, array('id' => 'currency_decimalPlaces')) !!}

               <div class="panel-body">

      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {{ l('Sent Date') }}
            <div class="form-control">{{abi_date_short($document->edocument_sent_at)}}</div>
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
         </div>
                  
        <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('document_ppd_percent') ? 'has-error' : '' }}">
           {{ l('Prompt Payment Discount (%)') }}
               <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                  data-content="{{ l('Comma separated list of days, as in: 3,17') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a -->
           {!! Form::text('document_ppd_percent', null, array('class' => 'form-control', 'id' => 'document_ppd_percent')) !!}
           {!! $errors->first('document_ppd_percent', '<span class="help-block">:message</span>') !!}
        </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
         </div>
{{--
         <div class="form-group col-lg-2 col-md-2 col-sm-2">
            <strong>{{ l('Customer Reference') }}</strong>
            <div class="form-control">{{ $document->reference_customer}}</div>
         </div>
--}}
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference_customer') ? 'has-error' : '' }}">
            <strong>{{ l('Customer Reference') }}</strong>
            {!! Form::text('reference_customer', null, array('class' => 'form-control', 'id' => 'reference_customer')) !!}
            {!! $errors->first('reference_customer', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('template_id') ? 'has-error' : '' }}">
            {{ l('Template') }}
            {!! Form::select('template_id', array('' => l('-- Please, select --', [], 'layouts')) + $templateList, null, array('class' => 'form-control', 'id' => 'template_id')) !!}
            {!! $errors->first('template_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('sequence_id') ? 'has-error' : '' }}">
            {{ l('Sequence') }}
            {!! Form::select('sequence_id', $sequenceList, old('sequence_id'), array('class' => 'form-control', 'id' => 'sequence_id')) !!}
            {!! $errors->first('sequence_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
            {{ l('Reference / Project') }}
            {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
            {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('sales_rep_id') ? 'has-error' : '' }}">
            {{ l('Sales Representative') }}
            {!! Form::select('sales_rep_id', array('0' => l('-- Please, select --', [], 'layouts')) + $salesrepList, null, array('class' => 'form-control', 'id' => 'sales_rep_id')) !!}
            {!! $errors->first('sales_rep_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('export_date') ? 'has-error' : '' }}">
               
@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') && $model_path=='customerorders' )

            <label for="export_date_form">{{ l('Export to FS') }}</label>
            <div  class="input-group">
               {!! Form::text('export_date_form', null, array('class' => 'form-control', 'id' => 'export_date_form', 'autocomplete' => 'off', 'onfocus' => 'this.blur()')) !!}
               {!! $errors->first('export_date', '<span class="help-block">:message</span>') !!}

              @if ($document->export_date)
              <span class="input-group-btn" title="{{ l('Reset', 'layouts') }}">
              <button class="btn btn-md btn-danger" type="button" onclick="$('#export_date_form').val('');">
                  <span class="fa fa-refresh"></span>
              </button>
              </span>
              @endif
            </div>
@endif

         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('document_date') ? 'has-error' : '' }}">
               {{ l('Document Date') }}
               {!! Form::text('document_date_form', null, array('class' => 'form-control', 'id' => 'document_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('document_date', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="col-lg-2 col-md-2 col-sm-2 {{ $errors->has('delivery_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Delivery Date') }}
               {!! Form::text('delivery_date_form', null, array('class' => 'form-control', 'id' => 'delivery_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('delivery_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

      <!-- /div>
      <div class="row" -->

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('payment_method_id') ? 'has-error' : '' }}">
            {{ l('Payment Method') }}
            {!! Form::select('payment_method_id', array('' => l('-- Please, select --', [], 'layouts')) + $payment_methodList, null, array('class' => 'form-control', 'id' => 'payment_method_id')) !!}
            {!! $errors->first('payment_method_id', '<span class="help-block">:message</span>') !!}
         </div>

@if ( $document->lines->count() == 0 )
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_id') ? 'has-error' : '' }}">
            {{ l('Currency') }}
            {!! Form::select('currency_id', $currencyList, null, array('class' => 'form-control', 'id' => 'currency_id', 'onchange' => 'get_currency_rate($("#currency_id").val())')) !!}
            {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_conversion_rate') ? 'has-error' : '' }}">

            {{ l('Conversion Rate') }}
            <div  class="input-group">
              {!! Form::text('currency_conversion_rate', null, array('class' => 'form-control', 'id' => 'currency_conversion_rate')) !!}
              {!! $errors->first('currency_conversion_rate', '<span class="help-block">:message</span>') !!}

              <span class="input-group-btn" title="{{ l('Update Conversion Rate') }}">
              <button class="btn btn-md btn-lightblue" type="button" onclick="get_currency_rate($('#currency_id').val());">
                  <span class="fa fa-money"></span>
              </button>
              </span>
            </div>

         </div>
@else

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_id') ? 'has-error' : '' }}">
            {{ l('Currency') }}
            <div class="form-control">{{ $document->currency->name }}</div>

            {!! Form::hidden('currency_id', $document->currency_id, array('name' => 'currency_id', 'id' => 'currency_id')) !!}
         </div>

    @if( $document->currency_id != \App\Configuration::get('DEF_CURRENCY') )
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_conversion_rate') ? 'has-error' : '' }}">
            {{ l('Conversion Rate') }}
            <div  class="input-group">
              {!! Form::text('currency_conversion_rate', null, array('class' => 'form-control', 'id' => 'currency_conversion_rate')) !!}
              {!! $errors->first('currency_conversion_rate', '<span class="help-block">:message</span>') !!}

              <span class="input-group-btn" title="{{ l('Update Conversion Rate') }}">
              <button class="btn btn-md btn-lightblue" type="button" onclick="get_currency_rate($('#currency_id').val());">
                  <span class="fa fa-money"></span>
              </button>
              </span>
            </div>
         </div>
    @endif

@endif

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('down_payment') ? 'has-error' : '' }}">
            {{ l('Down Payment') }}
            {!! Form::text('down_payment', null, array('class' => 'form-control', 'id' => 'down_payment')) !!}
            {!! $errors->first('down_payment', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            {!! Form::select('shipping_address_id', $customer->getAddressList(), null, array('class' => 'form-control', 'id' => 'shipping_address_id', 'onchange' => 'set_invoicing_address($(this).val())')) !!}
            {!! $errors->first('shipping_address_id', '<span class="help-block">:message</span>') !!}
         </div>
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
            {{ l('Warehouse') }}
            {!! Form::select('warehouse_id', $warehouseList, null, array('class' => 'form-control', 'id' => 'warehouse_id')) !!}
            {!! $errors->first('warehouse_id', '<span class="help-block">:message</span>') !!}
         </div>
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('shipping_method_id') ? 'has-error' : '' }}">
            {{ l('Shipping Method') }}
            {!! Form::select('shipping_method_id', array('' => l('-- Please, select --', [], 'layouts')) + $shipping_methodList, null, array('class' => 'form-control', 'id' => 'shipping_method_id')) !!}
            {!! $errors->first('shipping_method_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shipping_conditions') ? 'has-error' : '' }}">
            {{ l('Shipping Conditions') }}
            {!! Form::textarea('shipping_conditions', null, array('class' => 'form-control', 'id' => 'shipping_conditions', 'rows' => '1')) !!}
            {!! $errors->first('shipping_conditions', '<span class="help-block">:message</span>') !!}
         </div>

      </div>

      <div class="row">
        
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('carrier_id') ? 'has-error' : '' }}">
            {{ l('Carrier') }}
            <div class="form-control" id="carrier_id">{{ optional($document->carrier)->name }}</div>
            {{-- !! Form::select('carrier_id', array('0' => l('-- Please, select --', [], 'layouts')) + ($carrierList = []), null, array('class' => 'form-control', 'id' => 'carrier_id')) !!}
                        {!! $errors->first('carrier_id', '<span class="help-block">:message</span>') !! --}}
         </div>
{{--
        <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('number_of_packages') ? 'has-error' : '' }}">
           {{ l('Number of Packages') }}
           {!! Form::text('number_of_packages', null, array('class' => 'form-control', 'id' => 'number_of_packages')) !!}
           {!! $errors->first('number_of_packages', '<span class="help-block">:message</span>') !!}
        </div>
--}}
        <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('weight') ? 'has-error' : '' }}">
           {{ l('Weight') }} (<span class="text-success">{{ optional($weight_unit)->sign }}</span>)
           {!! Form::text('weight', null, array('class' => 'form-control', 'id' => 'weight')) !!}
           {!! $errors->first('weight', '<span class="help-block">:message</span>') !!}
        </div>

        <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('volume') ? 'has-error' : '' }}">
           {{ l('Volume') }} (<span class="text-success">{{ optional($volume_unit)->sign }}</span>)
           {!! Form::text('volume', null, array('class' => 'form-control', 'id' => 'volume')) !!}
           {!! $errors->first('volume', '<span class="help-block">:message</span>') !!}
        </div>

{{--
         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('tracking_number') ? 'has-error' : '' }}">
            {{ l('Tracking Number') }}
            {!! Form::text('tracking_number', null, array('class' => 'form-control', 'id' => 'tracking_number')) !!}
            {!! $errors->first('tracking_number', '<span class="help-block">:message</span>') !!}
         </div>
--}}
      </div>

      <div class="row">

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('notes') ? 'has-error' : '' }}" xstyle="margin-top: 20px;">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('notes_to_customer') ? 'has-error' : '' }}">
            {{ l('Notes to Customer') }}
            {!! Form::textarea('notes_to_customer', null, array('class' => 'form-control', 'id' => 'notes_to_customer', 'rows' => '2')) !!}
            {{ $errors->first('notes_to_customer', '<span class="help-block">:message</span>') }}
         </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('notes_from_customer') ? 'has-error' : '' }}">
            {{ l('Notes from Customer') }}
            {!! Form::textarea('notes_from_customer', null, array('class' => 'form-control', 'id' => 'notes_from_customer', 'rows' => '2')) !!}
            {{ $errors->first('notes_from_customer', '<span class="help-block">:message</span>') }}
         </div>

      </div>

               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">

                  <input type="hidden" id="nextAction" name="nextAction" value="" />

@if ($document->status=='draft' )
@php
  $hidden = $document->lines->count() == 0 ? 'hidden' : ''; 
@endphp
                  <button class="btn btn-success {{ $hidden }} " type="submit" onclick="this.disabled=true;$('#nextAction').val('saveAndConfirm');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Confirm', [], 'layouts')}}
                  </button>
@endif

                  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();" title="{{l('Back to Documents')}}">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>

                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;$('#nextAction').val('saveAndContinue');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Continue', [], 'layouts')}}
                  </button>
               </div>

<!-- Order header ENDS -->


    {!! Form::close() !!}
    