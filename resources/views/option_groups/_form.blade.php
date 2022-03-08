
<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('name', l('Option Group name')) !!}
        {!! Form::text('name', null, array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('public_name', l('Group public name')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('This will be displayed to the Customer and on commercial documentation.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('public_name', null, array('class' => 'form-control')) !!}
    </div>
</div>

<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('position', l('Position')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Use multiples of 10. Use other values to interpolate.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('position', null, array('class' => 'form-control')) !!}
    </div>
</div>

  {!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
  {!! link_to_route('optiongroups.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
