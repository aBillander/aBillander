
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


        <div class=" hidden row">

                   <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-active">
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

        </div>



        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('delivery_time') ? 'has-error' : '' }}">
                     {{ l('Delivery Time') }}
                         <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                            data-content="{{ l('Delivery Time in business days.') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a>
                     {!! Form::text('delivery_time', null, array('class' => 'form-control', 'id' => 'delivery_time')) !!}
                     {!! $errors->first('delivery_time', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('payment_days') ? 'has-error' : '' }}">
                     {{ l('Payment Day(s)') }}
                         <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                            data-content="{{ l('Comma separated list of days, as in: 3,17') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a>
                     {!! Form::text('payment_days', null, array('class' => 'form-control', 'id' => 'payment_days')) !!}
                     {!! $errors->first('payment_days', '<span class="help-block">:message</span>') !!}
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
