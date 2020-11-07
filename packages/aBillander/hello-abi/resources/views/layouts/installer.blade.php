@extends('installer::layouts.master')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header">
                    <h2>
                        {{ __('installer::main.title') }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-4">
                @include('installer::partials.overview')
            </div>

            <div class="col-md-9 col-sm-8">
                @yield('panel')
            </div>
        </div>
    </div>

@endsection
