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

         <!-- a href="{{ URL::to('auth/logout') }}">{{ Auth::user()->getFullName() }}</a --> <span style="color: #cccccc;">/</span> {{ l('Home') }}
    </h2>
</div>


{{-- ***************************************************** --}}


<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-2">
         <!-- div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Updates') }}
            </a>
         </div -->
      </div>

      
      <div class="col-lg-9 col-md-9 col-sm-10">
      <div class="jumbotron" style="background: no-repeat url('{{URL::to('/assets/theme/images/Dashboard.jpg')}}'); background-size: 100% auto;min-height: 200px; margin-top: 40px;">


      </div>
      </div>

   </div>
</div>

@endsection
