

              <div class="panel-body">

<div class="row">

         <div class="col-lg-4 col-md-4 col-sm-4 {{ $errors->has('order_document_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Date') }}
               {!! Form::text('order_document_date_form', null, array('class' => 'form-control', 'id' => 'order_document_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('order_document_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

         <!-- div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('template_id') ? 'has-error' : '' }}">
            {{ l('Template') }}
            {!! Form::select('template_id', $templateList, null, array('class' => 'form-control', 'id' => 'template_id')) !!}
            {!! $errors->first('template_id', '<span class="help-block">:message</span>') !!}
         </div -->

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('order_sequence_id') ? 'has-error' : '' }}">
            {{ l('Sequence') }}
            {!! Form::select('order_sequence_id', $order_sequenceList, old('order_sequence_id', \App\Configuration::getInt('DEF_CUSTOMER_ORDER_SEQUENCE')), array('class' => 'form-control', 'id' => 'order_sequence_id')) !!}
            {!! $errors->first('order_sequence_id', '<span class="help-block">:message</span>') !!}
         </div>

</div>

<div class="row">

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('order_status') ? 'has-error' : '' }}">
            {{ l('Status') }}
            {!! Form::select('order_status', $order_statusList, null, array('class' => 'form-control', 'id' => 'order_status')) !!}
            {!! $errors->first('order_status', '<span class="help-block">:message</span>') !!}
         </div>

</div>

                  </div>

                  <div class="panel-footer">

                        <a class="btn btn-success" href="javascript:void(0);" title="{{l('Confirm', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-select-documents').attr('action', '{{ route( 'customerorders.aggregate.orders' )}}');$('#form-select-documents').submit();return false;"><i class="fa fa-shopping-bag"></i> {{l('Confirm', 'layouts')}}</a>
                  
                  </div>
