
<div class="row">

         <div class="col-lg-3 col-md-3 col-sm-3 {{ $errors->has('due_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Date') }}
               {!! Form::text('due_date', null, array('class' => 'form-control', 'id' => 'due_date', 'autocomplete' => 'off')) !!}
               {!! $errors->first('due_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

         <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('name') ? 'has-error' : '' }}">
            {{ l('Name') }}
            {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
            {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
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
{!! link_to_route('productionsheets.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
