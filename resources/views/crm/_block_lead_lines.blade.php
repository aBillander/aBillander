
<div id="div_leads">
   <div class="table-responsive">

@if ($leadlines->count())
<table id="leads" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <!-- th>{{l('Position')}}</th -->
            <th>{{l('Name', 'leadlines')}}</th>
            <th>{{l('Status', 'layouts')}}</th>
            <!-- th class="text-center">{{l('Description')}}</th -->
            <th>{{l('Start', 'leadlines')}} /<br />{{l('Finish', 'leadlines')}}</th>
            <th>{{l('Due', 'leadlines')}}</th>
            <th>{{l('Assigned to', 'leadlines')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($leadlines as $leadline)
		<tr>
            <td>{{ $leadline->id }}</td>
            <!-- td>{{ $leadline->position }}</td -->
            <td>{{ $leadline->name }}

            </td>

            <td>{{ $leadline->status_name }}</td>

            <td>{{ abi_date_short($leadline->start_date) }}<br />{{ abi_date_short($leadline->finish_date) }}</td>
            <td @if ( $leadline->is_overdue ) ) class="danger" @endif>
            	{{ abi_date_short($leadline->due_date) }}
            </td>

            <td>{{ $leadline->assignedto->full_name }}</td>

			<td class="text-right button-pad">

                <a class="btn btn-sm btn-warning" href="{{ URL::to('leads/' . $leadline->lead->id.'/leadlines/' . $leadline->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}" target="_lead_line"><i class="fa fa-pencil"></i></a>

			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>
