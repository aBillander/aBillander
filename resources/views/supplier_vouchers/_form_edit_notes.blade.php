
        {!! Form::hidden('action', 'edit', array('id' => 'action')) !!}

<div class="row">
<div class="form-group col-lg-8 col-md-8 col-sm-8">
    {!! Form::label('name', l('Subject')) !!}
    <div class="form-control" style="background-color: #f9f9f9;">{{ $payment->name }}</div>
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('status_name', l('Status', [], 'layouts')) !!}
    <div class="form-control" style="background-color: #f9f9f9;">{{ \App\Payment::getStatusName($payment->status) }}</div>
</div>
</div>



<div class="row">

<div class="form-group col-lg-3 col-md-3 col-sm-3">
    <br />
    <a href="#" class="btn btn-info" id="show_extra_fields"><i class="fa fa-wrench"></i>&nbsp; {{l('Show more', [], 'layouts')}}</a>
</div>

<div class="form-group col-lg-3 col-md-3 col-sm-3">
<div class=" extrafield " style="display: none;">
    {!! Form::label('due_date_form', l('Due Date')) !!}
    {!! Form::text('due_date_form', null, array('class' => 'form-control')) !!}
</div>
</div>

<div name="voucher_payment_date" id="voucher_payment_date" class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('payment_date') ? 'has-error' : '' }}">
<div class=" extrafield " style="display: none;">
    {!! Form::label('payment_date_form', l('Payment Date')) !!}
    {!! Form::text('payment_date_form', null, array('class' => 'form-control')) !!}
</div>
</div>

<div class="form-group col-lg-3 col-md-3 col-sm-3">
    {!! Form::label('payment_type_id', l('Payment Type')) !!}
    {!! Form::select('payment_type_id', ['' => l('-- None --', [], 'layouts')] + $payment_typeList, null, array('class' => 'form-control')) !!}
</div>

</div>



<div class="row">

 <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
    {!! Form::label('notes', l('Notes', [], 'layouts')) !!}
    {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
    {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
 </div>

</div>


        <?php if (!isset($back_route)) $back_route = ''; ?>
        <input type="hidden" name="back_route" value="{{$back_route}}"/>

{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}

{{-- !! link_to_route('suppliervouchers.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !! --}}
{!! link_to( ($back_route != '' ? $back_route : 'suppliervouchers'), l('Cancel', [], 'layouts'), array('class' => 'btn btn-warning')) !!}
