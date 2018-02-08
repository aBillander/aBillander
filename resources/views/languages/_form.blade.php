
            <div class="row">
            <div class="form-group col-lg-5 col-md-5 col-sm-5">
                {!! Form::label('name', l('Language name')) !!}
                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                <p></p>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-3">
                {!! Form::label('iso_code', l('ISO Code')) !!}
                {!! Form::text('iso_code', null, array('class' => 'form-control')) !!}
                <p>{{l('e.g.: es')}}</p>
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-4">
                {!! Form::label('language_code', l('Language Code')) !!}
                {!! Form::text('language_code', null, array('class' => 'form-control')) !!}
                <p>{{l('e.g.: es-es')}}</p>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                {!! Form::label('date_format_lite', l('Date format')) !!}
                {!! Form::text('date_format_lite', null, array('class' => 'form-control')) !!}
                <p>{{l('e.g.: d/m/Y')}}</p>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                {!! Form::label('date_format_full', l('Date format (full)')) !!}
                {!! Form::text('date_format_full', null, array('class' => 'form-control')) !!}
                <p>{{l('e.g.: d/m/Y H:i:s')}}</p>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                {!! Form::label('date_format_lite_view', l('Date format - View')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('These date format apply to form datepickers') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
                {!! Form::text('date_format_lite_view', null, array('class' => 'form-control')) !!}
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                {!! Form::label('date_format_full_view', l('Date format (full) - View')) !!}
                {!! Form::text('date_format_full_view', null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="row">
                   <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-active">
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
        </div>

          {!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
          {!! link_to_route('languages.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
