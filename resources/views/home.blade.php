@extends('layouts.master')

@section('title') {{ l('Welcome') }} @parent @endsection


@section('content')

<div class="page-header">
    <h2>

@auth()         
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            {{ Auth::user()->getFullName() }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

         <!-- a href="{{ URL::to('auth/logout') }}">{{ Auth::user()->getFullName() }}</a --> <span style="color: #cccccc;">/</span> {{ l('Home') }}
@else
        Hello world!
@endauth

    </h2>        

</div>


<div class="jumbotron">

    <img src="{{URL::to('/assets/theme/images/Dashboard.jpg')}}" 
                        title=""
                        class="center-block"
                        style=" border: 1px solid #dddddd;
                                border-radius: 18px;
                                -moz-border-radius: 18px;
                                -khtml-border-radius: 18px;
                                -webkit-border-radius: 18px;">

</div>

@endsection
