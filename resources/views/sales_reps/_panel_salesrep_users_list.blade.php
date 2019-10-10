<!-- Customer Users --><!-- http://www.w3schools.com/bootstrap/bootstrap_ref_js_collapse.asp -->

<div id="panel_salesrep_users_detail">

    <div class="table-responsive">

        @if ($salesrep_users->count())
            <table id="aBook" class="table table-hover">
                <thead>
                <tr>
                    <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                    <th class="text-left">{{ l('Name', 'salesrepusers') }}</th>
                    <th class="text-left">{{l('Language', 'salesrepusers')}}</th>
                    <th class="text-left">{{ l('Allow give Customers ABCC access?', 'salesrepusers') }}</th>
                    <th class="text-center">{{l('Active', [], 'layouts')}}</th>
                    <th class="text-right">
                        <a href="#" class=" hide btn btn-sm btn-success create-salesrepuser"
                           title="{{l('Add New User')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($salesrep_users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="text-primary">
                                <strong>{{ $user->getFullName() }}</strong>
                            </div>
                            {{ $user->email }}</td>
                        <td>{{ $user->language->name }}</td>
                        <td class="text-center">
                            @if ($user->canGiveAbccAccess())
                                <i class="fa fa-check-square" style="color: #38b44a;"></i>
                            @else
                                <i class="fa fa-square-o" style="color: #df382c;"></i>
                            @endif
                        </td>
                        <td class="text-center">@if ($user->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i
                                    class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

                        <td class="text-right button-pad">
                            <a class=" hide btn btn-sm btn-grey" href="{ { route('salesrep.impersonate', [$user->id]) } }"
                               title="{{ l('Impersonate', 'salesrepusers') }}" target="_blank">&nbsp; <i class="fa fa-clock-o alert-success"></i>
                                &nbsp;</a>
                            {{--
                                                <a class=" hide btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal"
                                                        xhref="{{ URL::to('salesreps/' . $salesrep->id) . '/mail' }}"
                                                        href="{{ URL::to('mail') }}"
                                                        data-to_name = "{{ $user->firstname }} {{ $user->lastname }}"
                                                        data-to_email = "{{ $user->email }}"
                                                        data-from_name = "{{ abi_mail_from_name() }}"
                                                        data-from_email = "{{ abi_mail_from_address() }}"
                                                        onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>
                            --}}

                            <a class="btn btn-sm btn-warning update-salesrepuser" href="#" title="{{l('Edit', [], 'layouts')}}"
                               data-id="{{$user->id}}"><i class="fa fa-pencil"></i></a>


                            <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal"
                               href="{{ URL::to('salesrepusers/'.$user->id ) }}"
                               data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}"
                               data-title="{{ l('') }} :: ({{$user->id}}) {{ $user->getFullName() }} "
                               onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="modal-footer">

                <a href="#" class="btn xbtn-sm btn-success create-salesrepuser pull-right"
                   title="{{l('Add New User')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
            </div>

            <div class="alert alert-warning alert-block">
                <i class="fa fa-warning"></i>
                {{l('No records found', [], 'layouts')}}
            </div>
        @endif

    </div>

    {{--
          @include('layouts/modal_mail')
          @include('layouts/modal_delete')
    --}}

</div>
<!-- Customer Users ENDS -->
