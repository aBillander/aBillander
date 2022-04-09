
<div id="panel_actions">     

         

            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Commercial Actions') }} (<strong>{{ l('last :days days', ['days' => $action_range ?? 30]) }}</strong>)</h3>
               </div>
               <div class="panel-body">

<!-- Commercial Actions -->

<div id="panel_actions_detail">

    <div id="div_actions">
       <div class="table-responsive">

    @if ($actions->count())
    <table id="actions" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                <th class="text-left">{{ l('Name') }}</th>
                <th class="text-left">{{ l('Phone') }}</th>
                <th class="text-left">{{ l('Email') }}</th>
                <th class="text-left">{{ l('Address') }}</th>
                <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
                <th class="text-center">{{l('Is primary?')}}</th>
                <th class="text-center">{{l('Blocked', [], 'layouts')}}</th>
                <th class="text-center">{{l('Active', [], 'layouts')}}</th>
                <th class="text-right button-pad">

            <a href="{{ URL::to('customers/' . $customer->id . '/actions/create') . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#actions') }}" class="btn btn-sm btn-success" 
                    title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($actions as $action)
            <tr>
                <td>{{ $action->id }}</td>
                <td>{{ $action->firstname }} {{ $action->lastname }}
@if($action->job_title)
                    <br /><span class="text-info"><em>{{ $action->job_title }}</em></span>
@endif
@if($action->type)
                    <br /><span class="text-danger"><em>{{ $action->type_name }}</em></span>
@endif
                </td>
                <td>
                    {{ $action->phone }}<br />{{ $action->phone_mobile }}
                </td>
                <td>
                    {{ $action->email }}<br />{{ $action->website }}
                </td>
                <td>
@if ($action->address)
                    {{ $action->address->name_commercial }}<br />
                    {{ $action->address->address1 }} {{ $action->address->address2 }}<br />
                    {{ $action->address->postcode }} {{ $action->address->city }}, {{ $action->address->state->name }}<br />
                    {{ $action->address->country->name }}
@endif
                </td>
                <td class="text-center">
                    @if ($action->notes) 
                         <a href="javascript:void(0);">
                            <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                                    data-content="{{ $action->notes }}">
                                <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                            </button>
                         </a>
                    @endif
                </td>

                <td class="text-center">
                    @if ($action->is_primary)
                        <i class="fa fa-check-square" style="color: #38b44a;"></i>
                    @else
                        <i class="fa fa-square-o" style="color: #df382c;"></i>
                    @endif
                </td>
                <td class="text-center">
                    @if ($action->blocked)
                        <i class="fa fa-lock" style="color: #df382c;"></i>
                    @else
                        <i class="fa fa-unlock" style="color: #38b44a;"></i>
                    @endif
                </td>
                <td class="text-center">
                    @if ($action->active)
                        <i class="fa fa-check-square" style="color: #38b44a;"></i>
                    @else
                        <i class="fa fa-square-o" style="color: #df382c;"></i>
                    @endif
                </td>

                <td class="text-right button-pad">
                    @if (  is_null($action->deleted_at))
                    <a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                            xhref="{{ URL::to('customers/' . $customer->id) . '/mail' }}" 
                            href="{{ URL::to('mail') }}" 
                            data-to_name = "{{ $action->firstname }} {{ $action->lastname }}" 
                            data-to_email = "{{ $action->email }}" 
                            data-from_name = "{{ abi_mail_from_name() }}" 
                            data-from_email = "{{ abi_mail_from_address() }}" 
                            onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                    <a class="btn btn-sm btn-warning" href="{{ URL::to( 'customers/'.$customer->id.'/actions/' . $action->id . '/edit?back_route=' . urlencode('customers/' . $customer->id . '/edit#actions') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>


                    <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                            href="{{ URL::to('customers/'.$customer->id.'/actions/' . $action->id . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#actions') ) }}" 
                            data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                            data-title="{{ l('Contacts') }} :: ({{$action->id}}) {{ $action->full_name }} " 
                            onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

                    @else
                    <a class="btn btn-warning" href="{{ URL::to('customers/' . $customer->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                    <a class="btn btn-danger" href="{{ URL::to('customers/' . $customer->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    	<div class="pull-right">
            <a href="{{ URL::to('customers/' . $customer->id . '/actions/create') . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#actions') }}" class="btn btn-sm btn-success xpull-right" 
                    title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        
            &nbsp; <a href="{{ URL::to('customers/' . $customer->id . '/actions') }}" class="btn btn-sm btn-blue xpull-right" 
                    title="{{l('View all', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('View all', [], 'layouts')}}</a>
      </div>

      <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    @endif

       </div>
    </div>

</div>

<!-- Commercial Actions ENDS -->

               </div>
            </div>
               
</div>

{{--
    @include('layouts/modal_mail')
    @include('layouts/modal_delete')
--}}


@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {
      

   });

</script>
@endsection