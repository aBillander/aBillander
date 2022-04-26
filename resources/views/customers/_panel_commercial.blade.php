
            <div class="panel panel-primary" id="panel_commercial">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Commercial') }}</h3>
               </div>
               <div class="panel-body">

<!-- Comercial -->

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('invoice_sequence_id') ? 'has-error' : '' }}">
                     {{ l('Sequence for Invoices') }}
                     {!! Form::select('invoice_sequence_id', array('' => l('-- Please, select --', [], 'layouts')) + $sequenceList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('invoice_sequence_id', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('invoice_template_id') ? 'has-error' : '' }}">
                     
                     {{ l('Template for Invoices') }}
                     {!! Form::select('invoice_template_id', array('' => l('-- Please, select --', [], 'layouts')) + $invoices_templateList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('invoice_template_id', '<span class="help-block">:message</span>') !!}
                     
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('payment_method_id') ? 'has-error' : '' }}">
                     {{ l('Payment Method') }}
                     {!! Form::select('payment_method_id', array('0' => l('-- Please, select --', [], 'layouts')) + $payment_methodList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('payment_method_id', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('currency_id') ? 'has-error' : '' }}">
                     {{ l('Payment Currency') }}
                     {!! Form::select('currency_id', array('0' => l('-- Please, select --', [], 'layouts')) + $currencyList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="is_invoiceable">
                     {!! Form::label('is_invoiceable', l('Is Invoiceable?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('is_invoiceable', '1', true, ['id' => 'is_invoiceable_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('is_invoiceable', '0', false, ['id' => 'is_invoiceable_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="automatic_invoice">
                     {!! Form::label('automatic_invoice', l('Automatic Invoice?'), ['class' => 'control-label']) !!}
                         <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                            data-content="{{ l('Include this Customer in Automatic Invoice Generation Process.') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a>
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('automatic_invoice', '1', true, ['id' => 'automatic_invoice_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('automatic_invoice', '0', false, ['id' => 'automatic_invoice_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
@php
    $ibsa = [
        "0" => l('No', 'layouts'),
        "1" => l('One Invoice per Shipping Address'),
        "2" => l('One Invoice per Shipping Slip and Shipping Address'),
    ];
@endphp

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('invoice_by_shipping_address') ? 'has-error' : '' }}">
                     {!! Form::label('invoice_by_shipping_address', l('Invoice by Shipping Address?'), ['class' => 'control-label']) !!}
                     {!! Form::select('invoice_by_shipping_address', $ibsa, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('invoice_by_shipping_address', '<span class="help-block">:message</span>') !!}
                  </div>




                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('outstanding_amount_allowed') ? 'has-error' : '' }}">
                     {{ l('Outstanding Amount Allowed') }}
                     {!! Form::text('outstanding_amount_allowed', $customer->as_money('outstanding_amount_allowed', AbiContext::getContext()->language->currency), array('class' => 'form-control', 'id' => 'outstanding_amount_allowed')) !!}
                    {!! $errors->first('outstanding_amount_allowed', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('outstanding_amount') ? 'has-error' : '' }}">
                     {{ l('Outstanding Amount') }}
                     {!! Form::text('outstanding_amount', $customer->as_money('outstanding_amount', AbiContext::getContext()->language->currency), array('class' => 'form-control', 'id' => 'outstanding_amount', 'disabled' => "disabled")) !!}
                    {!! $errors->first('outstanding_amount', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('unresolved_amount') ? 'has-error' : '' }}">
                     {{ l('Unresolved Amount') }}
                     {!! Form::text('unresolved_amount', $customer->as_money('unresolved_amount', AbiContext::getContext()->language->currency), array('class' => 'form-control', 'id' => 'unresolved_amount', 'disabled' => "disabled")) !!}
                    {!! $errors->first('unresolved_amount', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">

                   <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     {!! Form::label('sales_equalization', l('Sales Equalization'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('sales_equalization', '1', true, ['id' => 'sales_equalization_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('sales_equalization', '0', false, ['id' => 'sales_equalization_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('customer_group_id') ? 'has-error' : '' }}">
                     {{ l('Customer Group') }}
                     {!! Form::select('customer_group_id', array('' => l('-- Please, select --', [], 'layouts')) + $customer_groupList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('customer_group_id', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('price_list_id') ? 'has-error' : '' }}">
                     {{ l('Price List') }}
                     {!! Form::select('price_list_id', array('' => l('-- Please, select --', [], 'layouts')) + $price_listList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('price_list_id', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('sales_rep_id') ? 'has-error' : '' }}">
                     {{ l('Sales Representative') }}
                     {!! Form::select('sales_rep_id', array('' => l('-- Please, select --', [], 'layouts')) + $salesrepList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('sales_rep_id', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
                     {{ l('Shipping Address') }}
                    <select class="form-control" name="shipping_address_id" id="shipping_address_id" @if ( count($aBook)==1 ) disabled="disabled" @endif>
                        @foreach ($aBook as $country)
                        <option {{ $customer->shipping_address_id == $country->id ? 'selected="selected"' : '' }} value="{{ $country->id }}">{{ $country->alias }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('shipping_address_id', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('shipping_method_id') ? 'has-error' : '' }}">
                     {{ l('Shipping Method') }}
                     {!! Form::select('shipping_method_id', array('' => l('-- Please, select --', [], 'layouts')) + $shipping_methodList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('shipping_method_id', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="col-md-4">
                      <div class="form-group {{ $errors->has('webshop_id') ? 'has-error' : '' }}">
                          {{ l('Webshop ID') }}
                          {!! Form::text('webshop_id', null, array('class' => 'form-control', 'id' => 'webshop_id')) !!}
                          {!! $errors->first('webshop_id', '<span class="help-block">:message</span>') !!}
                      </div>
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('payment_days') ? 'has-error' : '' }}">
                     {{ l('Payment Day(s)') }}
                         <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                            data-content="{{ l('Comma separated list of days, as in: 3,17') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a>
                     {!! Form::text('payment_days', null, array('class' => 'form-control', 'id' => 'payment_days')) !!}
                     {!! $errors->first('payment_days', '<span class="help-block">:message</span>') !!}
                  </div>
                  
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('no_payment_month') ? 'has-error' : '' }}">
                     {{ l('No Payment Month') }}
                     {{-- !! Form::text('no_payment_month', null, array('class' => 'form-control', 'id' => 'no_payment_month')) !! --}}

                     {!! Form::select('no_payment_month', array('0' => l('-- Please, select --', [], 'layouts')) + $monthList, null, array('class' => 'form-control', 'id' => 'no_payment_month')) !!}
                     {!! $errors->first('no_payment_month', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('discount_percent') ? 'has-error' : '' }}">
                     {{ l('Document Discount (%)') }}
                         <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                            data-content="{{ l('Comma separated list of days, as in: 3,17') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a -->
                     {!! Form::text('discount_percent', null, array('class' => 'form-control', 'id' => 'discount_percent')) !!}
                     {!! $errors->first('discount_percent', '<span class="help-block">:message</span>') !!}
                  </div>
                  
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('discount_ppd_percent') ? 'has-error' : '' }}">
                     {{ l('Prompt Payment Discount (%)') }}
                         <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                            data-content="{{ l('Comma separated list of days, as in: 3,17') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a -->
                     {!! Form::text('discount_ppd_percent', null, array('class' => 'form-control', 'id' => 'discount_ppd_percent')) !!}
                     {!! $errors->first('discount_ppd_percent', '<span class="help-block">:message</span>') !!}
                  </div>
                  
        </div>

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('order_template_id') ? 'has-error' : '' }}">
                     
                     {{ l('Template for Orders') }}
                     {!! Form::select('order_template_id', array('' => l('-- Please, select --', [], 'layouts')) + $orders_templateList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('order_template_id', '<span class="help-block">:message</span>') !!}
                     
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('shipping_slip_template_id') ? 'has-error' : '' }}">
                     
                     {{ l('Template for Shipping Slips') }}
                     {!! Form::select('shipping_slip_template_id', array('' => l('-- Please, select --', [], 'layouts')) + $shipping_slips_templateList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('shipping_slip_template_id', '<span class="help-block">:message</span>') !!}
                     
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('vat_regime') ? 'has-error' : '' }}">
                     
                     {{ l('VAT Regime') }}
                     {!! Form::select('vat_regime', \App\Models\Customer::getVatRegimeList(), null, array('class' => 'form-control')) !!}
                     {!! $errors->first('vat_regime', '<span class="help-block">:message</span>') !!}
                     
                  </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2">
                     {!! Form::label('accept_einvoice', l('Accept e-Invoice?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('accept_einvoice', '1', true, ['id' => 'accept_einvoice_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('accept_einvoice', '0', false, ['id' => 'accept_einvoice_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>


        </div>

<!-- Comercial ENDS -->

               </div>
               <div class="panel-footer text-right">
                  <input type="hidden" value="" name="tab_name" id="tab_name">
                  <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;$('#tab_name').val('commercial');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}
                  </button>
               </div>
            </div>
