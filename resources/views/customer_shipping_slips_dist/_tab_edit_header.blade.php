
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

         <div class="form-group col-lg-6 col-md-6 col-sm-6">
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
               
@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )

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
               {{ l('Order Date') }}
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
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>

<!-- Order header ENDS -->


    {!! Form::close() !!}
    