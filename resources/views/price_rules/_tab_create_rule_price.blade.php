

<div class="row">
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('from_quantity', l('From Quantity')) !!}
        {!! Form::text('from_quantity', old('from_quantity', 1), array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('price', l('Price')) !!}
        {!! Form::text('price', null, array('id' => 'price', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('currency_id', l('Currency')) !!}
        {!! Form::select('currency_id', ['' => l('-- All --', 'layouts')] + $currencyList, null, array('class' => 'form-control', 'id' => 'currency_id')) !!}
        {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
    </div>

</div>


               <div class="panel-footer text-right">
                  <a class="btn btn-link" href="{{ URL::to('pricerules') }}">{{l('Cancel', [], 'layouts')}}</a>
                  <button class="btn btn-success" type="submit" onclick="this.disabled=true;$('#rule_type').val('price');this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>
