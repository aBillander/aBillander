@extends('layouts.master')

@section('title') {{ l('Coming soon', [], 'layouts') }} @parent @endsection


@section('content')

<div class="page-header">
    <h2>
        {{ l('Coming soon...', [], 'layouts') }}
    </h2>        
</div>

<img src="{{URL::to('/assets/theme/images/Soon.jpg')}}" 
					title=""
                    class="center-block"
                    style=" xborder: 2px solid black;
                            border-radius: 18px;
                            -moz-border-radius: 18px;
                            -khtml-border-radius: 18px;
                            -webkit-border-radius: 18px;">
@endsection