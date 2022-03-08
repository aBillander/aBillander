
<div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('document_date_form') ? 'has-error' : '' }}">
               {{ l('Date') }}
               {!! Form::text('document_date_form', null, array('class' => 'form-control', 'id' => 'document_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('document_date_form', '<span class="help-block">:message</span>') !!}
         </div>


         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
            {{ l('Name') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Short Description for this Settlement.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
            {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
            {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
         </div>

@if ( $salesrep ) 
        {!! Form::hidden('sales_rep_id', $salesrep->id, array('id' => 'sales_rep_id')) !!}
@else
         <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('sales_rep_id') ? 'has-error' : '' }}">
            {!! Form::label('sales_rep_id', l('Sales Representative')) !!}
            {!! Form::select('sales_rep_id', array('0' => l('-- Please, select --', [], 'layouts')) + $salesrepList, null, array('class' => 'form-control', 'id' => 'sales_rep_id')) !!}
            {!! $errors->first('sales_rep_id', '<span class="help-block">:message</span>') !!}
         </div>
@endif

</div>

<div class="row">

     <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('document', l('Document')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('This Document Type will be used for Settlement calculation.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        <div class="form-control" id="document">{{ l('Invoice') }}</div>

        {{-- !! Form::hidden('document', null, array('id' => 'document_id')) !! --}}
     </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('date_from') ? 'has-error' : '' }}">
        {!! Form::label('date_from_form', l('Documents from')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('date_to') ? 'has-error' : '' }}">
        {!! Form::label('date_to_form', l('Documents to')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>

{{--
    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        { {-- Poor Man offset --} }
    </div>

     <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('document_due_date_form') ? 'has-error' : '' }}">
           {!! Form::label('document_due_date_form', l('Due Date')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('All Voucher Due Dates will be set to this value') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
           {!! Form::text('document_due_date_form', null, array('class' => 'form-control', 'id' => 'document_due_date_form', 'autocomplete' => 'off')) !!}
           {!! $errors->first('document_due_date_form', '<span class="help-block">:message</span>') !!}
     </div>
--}}
</div>

<div class="row">

           <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-paid_documents_only">
             {!! Form::label('paid_documents_only', l('Paid Documents Only?'), ['class' => 'control-label']) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Only Paid Documents will be selected.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('paid_documents_only', '1', false, ['id' => 'paid_documents_only_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('paid_documents_only', '0', true, ['id' => 'paid_documents_only_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
             </div>
           </div>

         <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('notes') ? 'has-error' : '' }}">
            {{ l('Notes', 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

</div>


{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('commissionsettlements.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
