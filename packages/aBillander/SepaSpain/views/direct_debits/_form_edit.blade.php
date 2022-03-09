
<div class="row">

         <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('document_date_form') ? 'has-error' : '' }}">
               {{ l('Date') }}
               {!! Form::text('document_date_form', null, array('class' => 'form-control', 'id' => 'document_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('document_date_form', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('scheme') ? 'has-error' : '' }}">
            {{ l('Scheme') }}
            {!! Form::select('scheme', $sepa_sp_schemeList, old('scheme'), array('class' => 'form-control', 'id' => 'scheme')) !!}
            {!! $errors->first('scheme', '<span class="help-block">:message</span>') !!}
         </div>


         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('bank_account_id') ? 'has-error' : '' }}">
            {{ l('Bank') }}
            {!! Form::select('bank_account_id', $bank_accountList, null, array('class' => 'form-control', 'id' => 'bank_account_id')) !!}
            {!! $errors->first('bank_account_id', '<span class="help-block">:message</span>') !!}
         </div>


         <div class=" hide form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('name') ? 'has-error' : '' }}">
            {{ l('Name') }}
            {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
            {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
         </div>

</div>
{{--
<div class="row">

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('sequence_id') ? 'has-error' : '' }}">
            {{ l('Sequence') }}
            {!! Form::select('sequence_id', $sequenceList, old('sequence_id'), array('class' => 'form-control', 'id' => 'sequence_id')) !!}
            {!! $errors->first('sequence_id', '<span class="help-block">:message</span>') !!}
         </div>

    <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('date_from_form', l('Vouchers from')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('date_to_form', l('Vouchers to')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>


</div>
--}}
<div class="row">

           <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-group_vouchers">
             {!! Form::label('group_vouchers', l('Group Vouchers?'), ['class' => 'control-label']) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('One Voucher for Customer and Date') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('group_vouchers', '1', false, ['id' => 'group_vouchers_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('group_vouchers', '0', true, ['id' => 'group_vouchers_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
             </div>
           </div>

           <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-discount_dd">
             {!! Form::label('discount_dd', l('Discount Remittance?'), ['class' => 'control-label']) !!}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('discount_dd', '1', false, ['id' => 'discount_dd_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('discount_dd', '0', true, ['id' => 'discount_dd_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
             </div>
           </div>
</div>

<div class="row">

         <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('notes') ? 'has-error' : '' }}">
            {{ l('Notes', 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

</div>


{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('sepasp.directdebits.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}

                <a class="btn xbtn-sm btn-blue pull-right" href="{{ URL::to('sepasp/directdebits/' . $directdebit->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-folder-open-o"></i> {{l('Show', [], 'layouts')}}</a>
