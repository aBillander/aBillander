
<div id="panel_actions">     

         

            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Commercial Actions') }} (<strong>{{ l('last :days days', ['days' => $action_range ?? 30]) }}</strong>) [{{ $actions->count() }}]</h3>
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
                <th class="text-left">{{ l('Name', 'actions') }}</th>
                <!-- th class="text-center">{{l('Action type', 'actions')}}</th -->
                <th class="text-center">{{l('Status', [], 'layouts')}}</th>
                <th class="text-left">{{ l('Description', 'actions') }}</th>
                <th class="text-left">{{ l('Priority', 'actions') }}</th>
                <th>{{l('Start', 'actions')}} /<br />{{l('Finish', 'actions')}}</th>
                <th>{{l('Due', 'actions')}}</th>
                <th class="text-center">{{l('Results', 'actions')}}</th>
                <th>{{l('Contact', 'actions')}}</th>
                <th>{{l('Assigned to', 'actions')}}</th>
                <th class="text-right button-pad">

            <a href="{{ URL::to('customers/' . $customer->id . '/actions/create') . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#actions') }}" class="btn btn-sm btn-success" 
                    title="{{l('Add New Item', [], 'layouts')}}"> <i class="fa fa-plus"></i> </a>

                    <a href="{{ URL::to('customers/' . $customer->id . '/actions') }}" class="btn btn-sm btn-blue xpull-right" 
                    title="{{l('View all', [], 'layouts')}}"> <i class="fa fa-eye"></i> </a>

                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($actions as $action)
            <tr>
                <td>{{ $action->id }}</td>
                <td>{{ $action->name }}
@if($action->actiontype)
                    <br /><span class="text-info"><em>{{ $action->actiontype->name }}</em></span>
@endif
                </td>
                <td>
                    {{ $action->status_name }}
                </td>
                <td>
                    @if ($action->description) 
                         <a href="javascript:void(0);">
                            <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                                    data-content="{{ $action->description }}">
                                <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                            </button>
                         </a>
                    @endif
                </td>

            <td>{{ $action->priority_name }}</td>

            <td>{{ abi_date_short($action->start_date) }}<br />{{ abi_date_short($action->finish_date) }}</td>
            <td>{{ abi_date_short($action->due_date) }}</td>
                <td class="text-center">
                    @if ($action->results) 
                         <a href="javascript:void(0);">
                            <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                                    data-content="{{ $action->results }}">
                                <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                            </button>
                         </a>
                    @endif
                </td>

                <td class="text-center">
@if($action->contact)
                    <span xclass="text-info">{{ $action->contact->full_name }}</span>
@else
                    -
@endif
                </td>
                <td class="text-center">
@if($action->salesrep)
                    <span xclass="text-info">{{ $action->salesrep->full_name }}</span>
@else
                    -
@endif
                </td>

                <td class="text-right button-pad">
                    @if (  is_null($action->deleted_at))
                    <a class="btn btn-sm btn-blue mail-item  hidden " data-html="false" data-toggle="modal" 
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
                            data-title="{{ l('Commercial Actions') }} :: ({{$action->id}}) {{ $action->name }} " 
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