
        <div>
            {!! Form::label('name', l('Name')) !!}
            {!! Form::text('name', null, array('class' => 'form-control')) !!}
        </div><br />
        <div>
            {!! Form::label('value', l('Value')) !!}
            {!! Form::text('value', null, array('class' => 'form-control')) !!}
        </div><br />
        <div>
            {!! Form::label('description', l('Description')) !!}
            {!! Form::textarea('description', null, array('class' => 'form-control', 'rows' => '3')) !!}
        </div><br />
        {!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
        {!! link_to_route('configurations.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
