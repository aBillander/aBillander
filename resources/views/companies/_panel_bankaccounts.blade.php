
            <div class="panel panel-primary" id="panel_bankaccounts">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Bank Accounts') }}</h3>
               </div>
               <div class="panel-body">


    <div id="div_company_bank_accounts">
       <div class="table-responsive">

    <table id="bank_accounts" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                <th class="text-left">{{ l('Bank Name') }}</th>
                <th class="text-left">{{ l('Iban') }}</th>
                <th class="text-left">{{ l('Swift') }}</th>
                <th class="text-left">{{ l('Suffix') }}</th>
                <th class="text-left">{{ l('Creditor ID') }}</th>
                <th class="text-center">{{l('Default?', [], 'layouts')}}</th>
                <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
                <!-- th class="text-center">{{l('Active', [], 'layouts')}}</th -->
                <th class="text-right button-pad"> 

        <div class="pull-right" style="padding-top: 4px;">
            <a href="#" class="btn btn-sm btn-success new-bankaccount" id="btn-new-bankaccount" 
                     data-target='#myModalBankAccount' data-id="{{ '' }}" data-toggle="modal" onClick="return false;" 
                     data-title="{{ l('New Bank Account') }}"
                    title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        </div>

                </th>
            </tr>
        </thead>
        <tbody>

    @if ($company->bankaccounts->count() > 0)

            @foreach ($company->bankaccounts as $bankaccount)
            <tr>
                <td>{{ $bankaccount->id }}</td>
                <td>{{ $bankaccount->bank_name }}</td>
                <td>{{ $bankaccount->iban }}</td>
                <td>{{ $bankaccount->swift }}</td>
                <td>{{ $bankaccount->suffix }}</td>
                <td>{{ $bankaccount->creditorid }}</td>

                <td  class="text-center">
                @if ( $company->bank_account_id == $bankaccount->id) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif
                </td>

                <td class="text-center">
                    @if ($bankaccount->notes) 
                         <a href="javascript:void(0);">
                            <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                                    data-content="{{ $bankaccount->notes }}">
                                <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                            </button>
                         </a>
                    @endif
                </td>
                <!-- td class="text-center">@if ($bankaccount->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td -->
                <td class="text-right button-pad">
                    @if (  is_null($bankaccount->deleted_at))

                    <a class="btn btn-sm btn-warning edit-bankaccount" href="#"
                     data-target='#myModalBankAccount' data-id="{{ $bankaccount->id }}" data-toggle="modal" onClick="return false;" 
                     data-title="{{ l('Edit Bank Account') }}" 
                     title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                      @if ( $company->bank_account_id != $bankaccount->id )
                    <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                            href="{{ route('companies.bankaccount.destroy', [$company->id, $bankaccount->id]) }}" 
                            data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                            data-title="{{ l('Bank Accounts') }} :: ({{$bankaccount->id}}) {{ $bankaccount->iban }} " 
                            onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                      @endif
                    @else
{{--
                    <a class="btn btn-warning" href="{{ URL::to('customers/' . $customer->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                    <a class="btn btn-danger" href="{{ URL::to('customers/' . $customer->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
--}}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else

        </tbody>
    </table>

    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    @endif

       </div>
    </div>



               </div>

            </div>


@include('companies._modal_bankaccount')

@include('layouts/modal_delete')


@section('scripts')    @parent

<script type="text/javascript">

    $(document).ready(function() {

    $("body").on('click', ".calculate_iban", function() {

            var url = "{{ route('bankaccounts.iban.calculate') }}";
            var token = "{{ csrf_token() }}";

            var payload = { 
                              ccc_entidad : $('#ccc_entidad').val(),
                              ccc_oficina : $('#ccc_oficina').val(),
                              ccc_control : $('#ccc_control').val(),
                              ccc_cuenta  : $('#ccc_cuenta').val(),
                          };

            $('#iban').parent().removeClass('has-success');

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(result){

                    // Poner borde de campo en naranja
                    // showAlertDivWithDelay("#msg-success");

                    console.log(result);

                    $('#iban').val(result.data.iban);

                    $('#iban').parent().addClass('has-success');
                }
            });

        });


    });

</script> 

@endsection
