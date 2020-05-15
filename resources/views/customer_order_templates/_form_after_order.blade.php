
<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('alias') ? 'has-error' : '' }}">
        {!! Form::label('alias', l('Alias')) !!}
        {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'alias')) !!}
        {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('name') ? 'has-error' : '' }}">
        {!! Form::label('name', l('Customer Order Template name')) !!}
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('customer_order') ? 'has-error' : '' }}">
        {!! Form::label('customer_order', l('Customer Order')) !!}

    @if( $document_id > 0 )
        <div class="form-control" id="customer_order">
            {{ $document_id }}
        </div>
        <input type="hidden" id="customer_order" name="customer_order" value="{{ $document_id }}" />
    @else
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" data-container="body" 
                                    data-content="{!! l('Customer Order <i>ID</i>. If Order is \'Draft\', use ID as seen in Order url on your browser.') !!}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('customer_order', null, array('class' => 'form-control', 'id' => 'customer_order')) !!}
        {!! $errors->first('customer_order', '<span class="help-block">:message</span>') !!}
    @endif

    </div>
</div>

<div class="row">
    <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-active">
     {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
     <div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('active', '1', true, ['id' => 'active_on']) !!}
           {!! l('Yes', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('active', '0', false, ['id' => 'active_off']) !!}
           {!! l('No', [], 'layouts') !!}
         </label>
       </div>
     </div>
    </div>

    <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('notes') ? 'has-error' : '' }}">
       {!! Form::label('notes', l('Notes', [], 'layouts')) !!}
       {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
       {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
    </div>
</div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('customerordertemplates.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}

