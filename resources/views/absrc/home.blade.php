@extends('absrc.layouts.master')

@section('title') {{ l('Welcome', 'absrc/layouts') }} @parent @endsection


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

         <!-- a href="{{{ URL::to('auth/logout') }}}">{{ Auth::user()->getFullName() }}</a --> <span style="color: #cccccc;">/</span> {{ l('Home', [], 'absrc/layouts') }}
    </h2>        
</div>

<div class="container hide">

{{-- 
    https://startbootstrap.com/template-overviews/sb-admin-2/
    https://startbootstrap.com/template-overviews/sb-admin/
--}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Sales Rep Dashboard</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-blue">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-comments fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">26</div>
                            <div>New Comments!</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">12</div>
                            <div>New Tasks!</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">124</div>
                            <div>New Orders!</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-support fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">13</div>
                            <div>Support Tickets!</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>

<!-- div class="container">
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
</div -->


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
{{-- HTML::image('img/picture.jpg', 'a picture', array('class' => 'thumb')) --}}
</div>


@endsection


@section('styles')    @parent

<style>

.huge {
  font-size: 40px;
}
.panel-blue {
  border-color: #337ab7;
}
.panel-blue > .panel-heading {
  border-color: #337ab7;
  color: white;
  background-color: #337ab7;
}
.panel-blue > a {
  color: #337ab7;
}
.panel-blue > a:hover {
  color: #23527c;
}
.panel-green {
  border-color: #5cb85c;
}
.panel-green > .panel-heading {
  border-color: #5cb85c;
  color: white;
  background-color: #5cb85c;
}
.panel-green > a {
  color: #5cb85c;
}
.panel-green > a:hover {
  color: #3d8b3d;
}
.panel-red {
  border-color: #d9534f;
}
.panel-red > .panel-heading {
  border-color: #d9534f;
  color: white;
  background-color: #d9534f;
}
.panel-red > a {
  color: #d9534f;
}
.panel-red > a:hover {
  color: #b52b27;
}
.panel-yellow {
  border-color: #f0ad4e;
}
.panel-yellow > .panel-heading {
  border-color: #f0ad4e;
  color: white;
  background-color: #f0ad4e;
}
.panel-yellow > a {
  color: #f0ad4e;
}
.panel-yellow > a:hover {
  color: #df8a13;
}

</style>

@endsection
