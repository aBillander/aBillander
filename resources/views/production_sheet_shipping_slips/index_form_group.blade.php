

              <div class="panel-body">

<div class="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('group_by_customer') ? 'has-error' : '' }}">
             {{ l('Should group?') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                        data-content="{{ l('Yes: One Invoice per Customer<br />No: One Invoice per Shipping Slip') }}">
                    <i class="fa fa-question-circle abi-help"></i>
                 </a>
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('group_by_customer', '1', true, ['id' => 'group_by_customer_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('group_by_customer', '0', false, ['id' => 'group_by_customer_off']) !!}
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
{{--
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

         <div class="col-lg-6 col-md-6 col-sm-6 {{ $errors->has('document_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Date') }}
               {!! Form::text('document_date_form', null, array('class' => 'form-control', 'id' => 'document_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('document_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>



<div class="form-group col-lg-6 col-md-6 col-sm-6">
  {{ l('Group by Address') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                        data-content="{{ l('Yes: One Invoice per Shipping Address') }}">
                    <i class="fa fa-question-circle abi-help"></i>
                 </a>
 <div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('group_by_shipping_address', '1', false, ['id' => 'group_by_shipping_address_on']) !!}
       {!! l('Yes', [], 'layouts') !!}
     </label>
   </div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('group_by_shipping_address', '0', true, ['id' => 'group_by_shipping_address_off']) !!}
       {!! l('No', [], 'layouts') !!}
     </label>
   </div>
 </div>
</div>


    <div class="form-group col-lg-6 col-md-6 col-sm-6">
      
            {{ l('testing') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Sí: se creará la Factura, pero no se marcarán los Albaranes como facturados') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
            {!! Form::select('testing', ['1' => 'Sí', '0' => 'No', ], 0, array('class' => 'form-control', 'id' => 'testing')) !!}
            {!! $errors->first('testing', '<span class="help-block">:message</span>') !!}

    </div>

</div>

                  </div>

                  <div class="panel-footer">

                        <a class="btn btn-info" href="javascript:void(0);" title="{{l('Confirm', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-select-documents').attr('action', '{{ route( 'productionsheet.create.invoices' )}}');$('#form-select-documents').submit();return false;"><i class="fa fa-money"></i> {{l('Confirm', 'layouts')}}</a>
                  
                  </div>
