@extends('layouts.master')

@section('title') {{ l('Welcome') }} @parent @stop


@section('content')

<div class="page-header">
    <h2>
         
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            {{ Auth::user()->getFullName() }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

         <!-- a href="{{{ URL::to('auth/logout') }}}">{{ Auth::user()->getFullName() }}</a --> <span style="color: #cccccc;">/</span> {{ l('Home') }}
    </h2>        
</div>




<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-1">
         <!-- div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Updates') }}
            </a>
         </div -->
      </div>

      
      <div class="col-lg-9 col-md-9 col-sm-10">
      <div class="jumbotron" style="background: no-repeat url('{{URL::to('/assets/theme/images/Dashboard.jpg')}}'); background-size: 100% auto;min-height: 200px">


<div class="panel panel-info" style="display:none">
  <div class="panel-heading">
    <h3 class="panel-title">{{ l('Updates') }}</h3>
  </div>
  <div class="panel-body">



<div id="div_loggers">
   <div class="table-responsive">

@if ($loggers && $loggers->count())
<table id="loggers" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left"></th>
            <th class="text-left">{{l('Date/Time', 'activityloggers')}}</th>
            <th class="text-left">{{l('Type', 'activityloggers')}}</th>
            <th class="text-left">{{l('Message', 'activityloggers')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loggers as $logger)
        <tr>
            <td class="auto-width">({{ $logger->id }}) &nbsp; </td>
            <td class="auto-width">{{ $logger->date_added }} &nbsp; </td>
            <td class="auto-width"><span class="log-{{ $logger->level_name }}-format">{{ $logger->level_name }}</span> &nbsp; </td>
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



  </div>
</div>


      </div>
      </div>

   </div>
</div>




{{--
<div class="jumbotron">

  <!-- h1>Jumbotron</h1>
  <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
  <p><a class="btn btn-primary btn-lg">Learn more</a></p -->
<img src="{{URL::to('/assets/theme/images/Dashboard.jpg')}}" 
                    title=""
                    class="center-block"
                    style=" border: 1px solid #dddddd;
                            border-radius: 18px;
                            -moz-border-radius: 18px;
                            -khtml-border-radius: 18px;
                            -webkit-border-radius: 18px;">
 HTML::image('img/picture.jpg', 'a picture', array('class' => 'thumb'))
</div>
 --}}

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

  .log-PENDING-format {
      color: blue;
      font-weight: bold;
  }
  .log-SUCCESS-format {
      color: #38b44a;
      font-weight: bold;
  }
  .log-ATTENTION-format {
      color: red;
      font-weight: bold;
  }
  .auto-width {
        width: 1px; 
        white-space: nowrap;
  }
</style>

@endsection
