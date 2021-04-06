
@section('modals')    @parent

<div class="modal fade" id="myModalBankAccount" role="dialog">
   <div class="modal-dialog modal-lg">
       <div class="modal-content" id="bankaccount-fields">

            <div class="modal-header alert-info"  style=" background-color: #d9edf7;
border-color: #bce8f1;
color: #3a87ad;
border-top-left-radius: 6px;
border-top-right-radius: 6px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalBankAccountLabel"></h4>
            </div>


  {!! Form::model(null, array('route' => array('companies.bankaccount', $company->id), 'method' => 'POST', 'class' => 'form')) !!}
  <input type="hidden" value="{{$company->id}}" name="bank_company_id" id="bank_company_id">
  <input type="hidden" value="" name="bank_account_id" id="bank_account_id">

            <div class="modal-body">

<!-- Datos generales -->

        <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 {!! $errors->has('bank_name') ? 'has-error' : '' !!}">
              {{ l('Bank Name') }}
              {!! Form::text('bank_name', null, array('class' => 'form-control', 'id' => 'bank_name')) !!}
              {!! $errors->first('bank_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>

        <div class=" hide  row">
            <div class="form-group col-lg-3 col-md-3 col-sm-3">
            <div class="well well-sm" xstyle="background-color: #d9edf7; border-color: #bce8f1; color: #3a87ad;">
               <b>{{ l('Código de Cuenta') }}</b>
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
                  
                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                      <br />
                      <a class="btn xbtn-sm btn-warning calculate_iban"><i class="fa fa-cogs"></i> {{ l('Calculate Iban') }}</a>
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

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    {{-- Poor man offset --}}
                  </div>
    
                <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-is_default">
                 {{ l('Default?', [], 'layouts') }}
                 <div>
                   <div class="radio-inline">
                     <label>
                       {!! Form::radio('is_default', '1', false, ['id' => 'is_default_on']) !!}
                       {!! l('Yes', [], 'layouts') !!}
                     </label>
                   </div>
                   <div class="radio-inline">
                     <label>
                       {!! Form::radio('is_default', '0', false, ['id' => 'is_featured_off']) !!}
                       {!! l('No', [], 'layouts') !!}
                     </label>
                   </div>
                 </div>
                </div>

        </div>

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('suffix') ? 'has-error' : '' !!}">
                    {{ l('Suffix') }}
                         <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Por defecto es "000", o el valor que asigne el Banco.') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a>
                    {!! Form::text('suffix', null, array('class' => 'form-control', 'id' => 'suffix')) !!}
                    {!! $errors->first('suffix', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('creditorid') ? 'has-error' : '' !!}">
                    {{ l('Creditor ID') }}
                         <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('El Identificador del Acreedor se calcula según la Norma SEPA, pero puede que su Banco requiera un valor diferente.') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a>
                    {!! Form::text('creditorid', null, array('class' => 'form-control', 'id' => 'creditorid')) !!}
                    {!! $errors->first('creditorid', '<span class="help-block">:message</span>') !!}
                  </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('bank_account_notes') ? 'has-error' : '' }}">
                   {{ l('Notes', [], 'layouts') }}
                   {!! Form::textarea('bank_account_notes', null, array('class' => 'form-control', 'id' => 'bank_account_notes', 'rows' => '2')) !!}
                   {!! $errors->first('bank_account_notes', '<span class="help-block">:message</span>') !!}
                </div>

        </div>

<!-- Datos generales ENDS -->
                
            </div>

            <div class="modal-footer">
               <div xclass="panel-footer text-right">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                    <button class="btn btn-success" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}
                  </button>
               </div>
            </div>


     {!! Form::close() !!}

      </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}


@section('scripts') @parent 

<script>

    $(document).ready(function () {

            $('.new-bankaccount').click(function (evnt) {

            //   var panel = $("#bankaccount-fields");
            //   var secure_key = $(this).attr('data-id');
            //   var url = "{ { route('xxx', [":id"]) } }";
            //    var href = $(this).attr('href');
                var title = $(this).attr('data-title');

                $('#myModalBankAccountLabel').html(title);
                // Clean up data
                $('#bank_account_id').val('');
                $('#bank_name').val('');
                $('#ccc_entidad').val('');
                $('#ccc_oficina').val('');
                $('#ccc_control').val('');
                $('#ccc_cuenta').val('');
                $('#iban').val('');
                $('#swift').val('');
                $('#suffix').val('');
                $('#creditorid').val('');
                $('#bank_account_notes').val('');

                // Default bank account?
                $("input[name=is_default][value=" + 1 + "]").prop('checked', true);

                $('#iban').parent().removeClass('has-success');

               // Show Modal
               $('#myModalBankAccount').modal({show: true});

               return false;

           });



            $('.edit-bankaccount').click(function (evnt) {

            //   var panel = $("#bankaccount-fields");
               var bank_account_id = $(this).attr('data-id');
               var url = "{{ route('companies.getbankaccount', [$company->id, ':aid']) }}";
            //    var href = $(this).attr('href');
                var title = $(this).attr('data-title');

                $('#myModalBankAccountLabel').html(title);

               url = url.replace(":aid", bank_account_id);

            //   panel.addClass('loading');

               $.get(url, function(result){

                    // Set up data
                    $('#bank_account_id').val(result.id);
                    $('#bank_name').val(result.bank_name);
                    $('#ccc_entidad').val(result.ccc_entidad);
                    $('#ccc_oficina').val(result.ccc_oficina);
                    $('#ccc_control').val(result.ccc_control);
                    $('#ccc_cuenta').val(result.ccc_cuenta);
                    $('#iban').val(result.iban);
                    $('#swift').val(result.swift);
                    $('#suffix').val(result.suffix);
                    $('#creditorid').val(result.creditorid);
                    $('#bank_account_notes').val(result.notes);

                    // Default bank account?
                    if ( result.is_default > 0 )
                        $("input[name=is_default][value=" + 1 + "]").prop('checked', true);
                    else
                        $("input[name=is_default][value=" + 0 + "]").prop('checked', true);

            //         panel.removeClass('loading');
                     $("[data-toggle=popover]").popover();

                     console.log(result);
                     
               }).done( function() { 

                    $('#iban').parent().removeClass('has-success');

                    $('#myModalBankAccount').modal({show: true});

                });

               return false;

           });

    });

</script>

@endsection