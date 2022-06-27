@extends('installer::layouts.master')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12" xstyle="background-color: pink">

                <div class="page-header" xstyle="background-color: yellow">
                    <img class="pull-right" src="{{ asset('assets/theme/images/laravatar.png') }}" title="Lara Billander :: The Girl with the Dragon Tattoo" xheight="176" class="center-block" style="margin-top: -30px; margin-right: 15px; padding: 10px; border-radius: 16px;" alt="Lara Billander" width="80">
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
