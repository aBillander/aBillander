@extends('layouts.master')

@section('title') {{ l('Todos') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('todos/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <a id="btn1" href="#myHelpModal" class="btn btn-sm btn-behance" xdata-backdrop="false" data-toggle="modal"> <i class="fa fa-life-saver"></i>  {{l('Help', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Todos') }}
    </h2>        
</div>

<div id="div_todos">
   <div class="table-responsive">

@if ($todos->count())
<table id="todos" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Todo name')}}</th>
            <th>{{l('Description')}}</th>
            <th>{{l('Created at')}}</th>
            <th>{{l('Due date')}}</th>
            <th>{{l('Url')}}</th>
            <th class="text-center">{{l('Completed?')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($todos as $todo)
		<tr>
			<td>{{ $todo->id }}</td>
            <td>{{ $todo->name }}</td>
            <td>
                @if ($todo->description)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $todo->description }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
            <td>{{ abi_date_form_short($todo->created_at->toDateString()) }}</td>
            <td @if ( $todo->isOverdue() ) class="danger" @endif >{{ abi_date_form_short($todo->due_date) }}</td>
            <td>{{ $todo->shortUrl() }}</td>

            <td class="text-center">
                <a class="btn btn-sm toggle-item" id="item_{{ $todo->id }}"> 
{{--
                        href="{{ URL::to('todos/' . $todo->id ) }}" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ l('Todos') }} :: ({{$todo->id}}) {{ $todo->name }} " 
                        onClick="return false;" title="{{l('Delete', [], 'layouts')}}">
--}}
                        @if ($todo->completed) 
                            <i class="fa fa-check-square" style="color: #38b44a;"></i> 
                        @else 
                            <i class="fa fa-square-o" style="color: #df382c;"></i> 
                        @endif
                </a></td>

			<td class="text-right button-pad">
                @if (  is_null($todo->deleted_at))
                @if (  $todo->url && !$todo->completed  )
                <a class="btn btn-sm btn-lightblue" href="{{ $todo->url }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"><i class="fa fa-mail-forward"></i></a>
                @endif
                <a class="btn btn-sm btn-warning" href="{{ URL::to('todos/' . $todo->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('todos/' . $todo->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Todos') }} :: ({{$todo->id}}) {{ $todo->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('todos/' . $todo->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('todos/' . $todo->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
{!! $todos->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $todos->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection

@include('layouts/modal_delete')

{{--  --}}
@include('todos._modal_help')
{{--  --}}

@section('scripts')

@parent

<script type="text/javascript">
    $(document).ready(function () {
        $('.toggle-item').click(function (evnt) {

            var item_id = $(this).attr('id');
            var href = $(this).attr('href');

            evnt.preventDefault();
   
   // https://laracasts.com/discuss/channels/general-discussion/laravel-ajax-post-updating
   // http://stackoverflow.com/questions/27914559/patch-ajax-request-in-laravel

/* */

            $.ajax({
              type: 'PATCH',
              url: href,
              data: "item_id="+item_id+"&toggleStatus=1&_token={{ csrf_token() }}",  
              success: function(data) {

                    var status;
                    var obj = JSON.parse(data);

                    status = obj.active > 0 ? '<i class="fa fa-check-square" style="color: #38b44a;">' : '<i class="fa fa-square-o" style="color: #df382c;">' ;
                    $('#item_'+obj.item_id).html(status);
              }
            });

            // Seems type=POST with data+'&_method=PATCH' won't work!

/* */

            return false;
        });
    });
</script>

@endsection