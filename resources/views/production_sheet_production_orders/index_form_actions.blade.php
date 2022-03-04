

              <div class="panel-body">
{{--
<div class="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('should_group') ? 'has-error' : '' }}">
             {{ l('Should group?') }}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('should_group', '1', true, ['id' => 'should_group_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('should_group', '0', false, ['id' => 'should_group_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
             </div>
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('status') ? 'has-error' : '' }}">
            {{ l('Status') }}
            {!! Form::select('status', $statusList, null, array('class' => 'form-control', 'id' => 'status')) !!}
            {!! $errors->first('status', '<span class="help-block">:message</span>') !!}
         </div>

</div>

<div class="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('template_id') ? 'has-error' : '' }}">
            {{ l('Template') }}
            {!! Form::select('template_id', $templateList, null, array('class' => 'form-control', 'id' => 'template_id')) !!}
            {!! $errors->first('template_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('sequence_id') ? 'has-error' : '' }}">
            {{ l('Sequence') }}
            {!! Form::select('sequence_id', $sequenceList, old('sequence_id'), array('class' => 'form-control', 'id' => 'sequence_id')) !!}
            {!! $errors->first('sequence_id', '<span class="help-block">:message</span>') !!}
         </div>

</div>
--}}

<div class="row">

         <div class="col-lg-6 col-md-6 col-sm-6 {{ $errors->has('orders_finish_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Finish Date') }}
               {!! Form::text('orders_finish_date_form', null, array('class' => 'form-control', 'id' => 'orders_finish_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('orders_finish_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>
         
         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('orders_warehouse_id') ? 'has-error' : '' }}">
            <br />
            {{ l('Warehouse') }}
            {!! Form::select('orders_warehouse_id', array('0' => l('-- Default --', [], 'layouts')) + $warehouseList, null, array('class' => 'form-control', 'id' => 'orders_warehouse_id')) !!}
            {!! $errors->first('orders_warehouse_id', '<span class="help-block">:message</span>') !!}
         </div>
{{--
         <div class="col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shippingslip_delivery_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Delivery Date') }}
               {!! Form::text('delivery_date_form', null, array('class' => 'form-control', 'id' => 'delivery_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('shippingslip_delivery_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>
--}}
</div>


                  </div>

                  <div class="panel-footer">

                        <a class="btn btn-info" href="javascript:void(0);" title="{{l('Confirm', [], 'layouts')}}" xonclick = "this.disabled=true;$('#form-select-documents').attr('action', '{{ route( 'productionsheet.productionorders.bulk.finish' )}}');$('#form-select-documents').submit();return false;" disabled="disabled"><i class="fa fa-cubes"></i> {{l('Confirm', 'layouts')}}</a>
                  
                  </div>
