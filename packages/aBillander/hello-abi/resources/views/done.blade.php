@extends('installer::layouts.installer')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('panel')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ __('installer::main.done.title') }}</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-success">
                <p>{{ __('installer::main.done.success') }}</p>
            </div>
            <hr>
            <p>{{ __('installer::main.done.body') }}</p>
        </div>
        <div class="panel-footer text-right">
            <a class="btn btn-primary" href="{{ url('/') }}">
                {{ __('installer::main.done.link') }}
            </a>
        </div>
    </div>

@endsection
