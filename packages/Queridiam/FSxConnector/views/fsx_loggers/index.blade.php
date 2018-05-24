@extends('layouts.master')

@section('title')  {{ l('aBillander LOG') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        @if( $fsx_logger_errors )
        <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="return false;" 
                title="{{l('ERRORS')}}"><i class="fa fa-hand-stop-o"></i> {{ $fsx_logger_errors }} {{l('ERROR(S)')}}</a>
        @endif
        @if( $fsx_logger_warnings )
        <a href="javascript:void(0);" class="btn btn-sm btn-warning" onclick="return false;" 
                title="{{l('WARNINGS')}}"><i class="fa fa-hand-stop-o"></i> {{ $fsx_logger_warnings }} {{l('WARNING(S)')}}</a>
        @endif
        <a href="{{{ URL::to('fsx/fsxlog/empty') }}}" class="btn btn-sm btn-success" 
                title="{{l('Empty LOG')}}"><i class="fa fa-scissors"></i> {{l('Empty LOG')}}</a>
    </div>
    <h2>
        {{ l('aBillander LOG') }}
    </h2>        
</div>

<div id="div_fsx_loggers">
   <div class="table-responsive">

@if ($fsx_loggers->count())
<table id="fsx_loggers" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('Date/Time')}}</th>
            <th class="text-left">{{l('Type')}}</th>
            <th class="text-left">{{l('Message')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fsx_loggers as $fsx_logger)
        <tr>
            <td>{{ $fsx_logger->id }}</td>
            <td>{{ $fsx_logger->date_added }} &nbsp; {{ sprintf( "(.%04s)",   intval(intval($fsx_logger->secs_added)/100.0) ) }}</td>
            <td><span class="log-{{ $fsx_logger->type }}-format">{{ $fsx_logger->type }}</span></td>
            <td>{!! $fsx_logger->message !!}</td>
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

@stop


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


