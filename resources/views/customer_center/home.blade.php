@extends('customer_center.layouts.master')

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

         <!-- a href="{{{ URL::to('auth/logout') }}}">{{ Auth::user()->getFullName() }}</a --> <span style="color: #cccccc;">/</span> {{ l('Home', [], 'customerhome') }}
    </h2>        
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">CUSTOMER Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as <strong>CUSTOMER</strong>!
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
{ {-- HTML::image('img/picture.jpg', 'a picture', array('class' => 'thumb')) --} }
</div>
--}}

@stop
