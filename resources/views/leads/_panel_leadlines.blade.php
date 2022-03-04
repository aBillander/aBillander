
            <div class="panel panel-primary" id="panel_leadlines">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Lead Lines') }}</h3>
               </div>

              <div class="panel-body">


<div id="div_leadlines">
   <div class="table-responsive">

@if ($leadlines->count())
<table id="leadlines" class="table table-hover">
  <thead>
    <tr>
      <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <!-- th>{{l('Position')}}</th -->
            <th>{{l('Name')}}</th>
            <th>{{l('Status', 'layouts')}}</th>
            <!-- th class="text-center">{{l('Description')}}</th -->
            <th>{{l('Start')}} /<br />{{l('Finish')}}</th>
            <th>{{l('Due')}}</th>
            <th class="text-center">{{l('Results')}}</th>
            <th>{{l('Assigned to')}}</th>
            <th class="text-right"> 
              <a href="{{ URL::to('leads/'.$lead->id.'/leadlines/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Lead Line')}}"><i class="fa fa-plus"></i> &nbsp;{{l('Add New', [], 'layouts')}}</a>
            </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($leadlines as $leadline)
    <tr>
            <td>{{ $leadline->id }}</td>
            <!-- td>{{ $leadline->position }}</td -->
            <td><strong>{{ $leadline->name }}</strong><br />
                {!! $leadline->description !!}

            </td>

            <td>{{ $leadline->status_name }}</td>

            <td>{{ abi_date_short($leadline->start_date) }}<br />{{ abi_date_short($leadline->finish_date) }}</td>
            <td>{{ abi_date_short($leadline->due_date) }}</td>

            <td width="40%">{!! $leadline->results !!}
            </td>

            <td>{{ $leadline->assignedto->full_name }}</td>

      <td class="text-right button-pad">
                @if (  is_null($leadline->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('leads/' . $lead->id.'/leadlines/' . $leadline->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('leads/' . $lead->id.'/leadlines/' . $leadline->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Lead Lines') }} :: ({{$leadline->id}}) {{ $leadline->name }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('leadlines/' . $leadline->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('leadlines/' . $leadline->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}

              <a href="{{ URL::to('leads/'.$lead->id.'/leadlines/create') }}" class="btn btn-sm btn-success pull-right" 
                style="text-decoration: none !important" title="{{l('Add New Lead Line')}}"><i class="fa fa-plus"></i> &nbsp;{{l('Add New', [], 'layouts')}}</a>
</div>
@endif

   </div>
</div>


              </div>

            </div>
