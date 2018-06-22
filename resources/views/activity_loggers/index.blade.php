@extends('layouts.master')

@section('title')  {{ l('aBillander LOG') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        @if( $logger_errors )
        <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="return false;" 
                title="{{l('ERRORS')}}"><i class="fa fa-hand-stop-o"></i> {{ $logger_errors }} {{l('ERROR(S)')}}</a>
        @endif
        @if( $logger_warnings )
        <a href="javascript:void(0);" class="btn btn-sm btn-warning" onclick="return false;" 
                title="{{l('WARNINGS')}}"><i class="fa fa-hand-stop-o"></i> {{ $logger_warnings }} {{l('WARNING(S)')}}</a>
        @endif
        @if ($loggers->count())

        @if (count($log_names) > 1)

          {!! Form::model(Request::all(), array('route' => 'activityloggers.index', 'method' => 'GET', 'style' => "display: inline-block; margin: 0;")) !!}

            {!! Form::select('log_name', array('' => l('-- Please, select --', [], 'layouts')) + $log_names, null, array('class' => 'xform-control', 'id' => 'log_name', 'onchange' => 'this.form.submit()')) !!}

          {!! Form::close() !!}

        @endif

        <a href="{{{ URL::to('activityloggers/empty') }}}" class="btn btn-sm btn-success" 
                title="{{l('Empty LOG')}}"><i class="fa fa-scissors"></i> {{l('Empty LOG')}}</a>
        @endif
    </div>
    <h2>
        {{ l('aBillander LOG') }}
    </h2>        
</div>

<div id="div_loggers">
   <div class="table-responsive">

@if ($loggers->count())
<table id="loggers" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('User ID')}}</th>
            <th class="text-left">{{l('LOG Name')}}</th>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('Date/Time')}}</th>
            <th class="text-left">{{l('Type')}}</th>
            <th class="text-left">{{l('Message')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loggers as $logger)
        <tr>
            <td>{{ $logger->user_id }}</td>
            <td>{{ $logger->log_name }}</td>
            <td>{{ $logger->id }}</td>
            <td>{{ $logger->date_added }} &nbsp; {{ sprintf( "(.%04s)",   intval(intval($logger->secs_added)/100.0) ) }}</td>
            <td><span class="log-{{ $logger->level_name }}-format">{{ $logger->level_name }}</span></td>
            <td>{!! $logger->message !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{!! $loggers->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $loggers->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection


@section('styles')    @parent

<style>
  .log-showoff-format {
      color: #3a87ad;;
      font-weight: bold;
  }

  .log-INFO-format {
      color: black;
  }
  .log-WARNING-format {
      color: #e7a413;
      font-weight: bold;
  }
  .log-ERROR-format {
      color: red;
      font-weight: bold;
  }
  .log-TIMER-format {
      color: blue;
      font-weight: bold;
  }
</style>

@endsection


