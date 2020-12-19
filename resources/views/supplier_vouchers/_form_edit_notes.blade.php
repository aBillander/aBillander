
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
