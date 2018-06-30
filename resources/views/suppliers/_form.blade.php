
        <div class="row">
            <div class="form-group col-lg-5 col-md-5 col-sm-5 {!! $errors->has('name_fiscal') ? 'has-error' : '' !!}">
                  {!! Form::label('name_fiscal', l('Fiscal Name')) !!}
                  {!! Form::text('name_fiscal', null, array('class' => 'form-control', 'id' => 'name_fiscal')) !!}
                  {!! $errors->first('name_fiscal', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-3 {!! $errors->has('identification') ? 'has-error' : '' !!}">
                  {!! Form::label('identification', l('Identification')) !!}
                  {!! Form::text('identification', null, array('class' => 'form-control', 'id' => 'identification')) !!}
                  {!! $errors->first('identification', '<span class="help-block">:message</span>') !!}
            </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('website') ? 'has-error' : '' !!}">
                {!! Form::label('website', l('Website')) !!}
                {!! Form::text('website', null, array('class' => 'form-control', 'id' => 'website')) !!}
                {!! $errors->first('website', '<span class="help-block">:message</span>') !!}
              </div>
        </div>

        <div class="row">

            <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('language_id') ? 'has-error' : '' !!}">
                {!! Form::label('language_id', l('Language')) !!}
                {!! Form::select('language_id', $languageList, null, array('class' => 'form-control', 'id' => 'language_id')) !!}
                {!! $errors->first('language_id', '<span class="help-block">:message</span>') !!}
            </div>


           <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('currency_id') ? 'has-error' : '' }}">
              {!! Form::label('currency_id', l('Currency')) !!}
              {!! Form::select('currency_id', $currencyList, null, array('class' => 'form-control', 'id' => 'currency_id')) !!}
              {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
           </div>

             <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('payment_method_id') ? 'has-error' : '' }}">
                {!! Form::label('payment_method_id', l('Payment Method')) !!}
                {!! Form::select('payment_method_id', array('0' => l('-- Please, select --', [], 'layouts')) + $payment_methodList, null, array('class' => 'form-control', 'id' => 'payment_method_id')) !!}
                {!! $errors->first('payment_method_id', '<span class="help-block">:message</span>') !!}
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
          {!! link_to_route('suppliers.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
