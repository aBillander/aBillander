
            <div class="panel panel-primary" id="panel_accounting">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Contabilidad') }}</h3>
               </div>
               <div class="panel-body">

<!-- Accounting -->

        <div class="row">

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                     {{ l('ID', 'layouts') }}
                     <div class="form-control">{{ $customer->id }}</div>
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('accounting_id') ? 'has-error' : '' }}">
                     {{ l('Accounting ID') }}
                     {!! Form::text('accounting_id', null, array('class' => 'form-control', 'id' => 'accounting_id')) !!}
                    {!! $errors->first('accounting_id', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

<!-- Accounting ENDS -->

               </div>
               <div class="panel-footer text-right">
                  <!-- input type="hidden" value="" name="tab_name" id="tab_name" -->
                  <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;$('#tab_name').val('accounting');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}
                  </button>
               </div>
            </div>
