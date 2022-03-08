

  {!! Form::model($supplier->bankaccount, array('route' => array('suppliers.bankaccount', $supplier->id), 'method' => 'POST', 'class' => 'form')) !!}
  <input type="hidden" value="{{$supplier->id}}" name="bank_supplier_id" id="bank_supplier_id">

            <div class="panel panel-primary" id="panel_bankaccounts">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Bank Accounts') }}</h3>
               </div>
               <div class="panel-body">

<!-- Datos generales -->

        <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 {!! $errors->has('bank_name') ? 'has-error' : '' !!}">
              {{ l('Bank Name') }}
              {!! Form::text('bank_name', null, array('class' => 'form-control', 'id' => 'bank_name')) !!}
              {!! $errors->first('bank_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-lg-3 col-md-3 col-sm-3">
            <div class="well well-sm" xstyle="background-color: #d9edf7; border-color: #bce8f1; color: #3a87ad;">
               <b>{{ l('Código Cuenta Cliente') }}</b>
            </div>
            </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('ccc_entidad') ? 'has-error' : '' !!}">
                    {{ l('Entidad') }}
                    {!! Form::text('ccc_entidad', null, array('class' => 'form-control', 'id' => 'ccc_entidad')) !!}
                    {!! $errors->first('ccc_entidad', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('ccc_oficina') ? 'has-error' : '' !!}">
                    {{ l('Oficina') }}
                    {!! Form::text('ccc_oficina', null, array('class' => 'form-control', 'id' => 'ccc_oficina')) !!}
                    {!! $errors->first('ccc_oficina', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-1 col-md-1 col-sm-1 {!! $errors->has('ccc_control') ? 'has-error' : '' !!}">
                    {{ l('Control') }}
                    {!! Form::text('ccc_control', null, array('class' => 'form-control', 'id' => 'ccc_control')) !!}
                    {!! $errors->first('ccc_control', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('ccc_cuenta') ? 'has-error' : '' !!}">
                    {{ l('Cuenta') }}
                    {!! Form::text('ccc_cuenta', null, array('class' => 'form-control', 'id' => 'ccc_cuenta')) !!}
                    {!! $errors->first('ccc_cuenta', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('iban') ? 'has-error' : '' !!}">
                    {{ l('Iban') }}
                    {!! Form::text('iban', null, array('class' => 'form-control', 'id' => 'iban')) !!}
                    {!! $errors->first('iban', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('swift') ? 'has-error' : '' !!}">
                    {{ l('Swift') }}
                    {!! Form::text('swift', null, array('class' => 'form-control', 'id' => 'swift')) !!}
                    {!! $errors->first('swift', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

<!-- Datos generales ENDS -->

               </div>
               <div class="panel-footer text-right">
                  <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}
                  </button>
               </div>
            </div>


     {!! Form::close() !!}
