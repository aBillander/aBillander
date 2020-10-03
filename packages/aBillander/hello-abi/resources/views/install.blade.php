@extends('installer::layouts.installer')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('panel')

    <form class="" action="{{ route('installer::install') }}" method="post">
        {{ csrf_field() }}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('installer::main.install.title') }}</h3>
            </div>

            <div class="panel-body">
                <div class="alert alert-success">
                    {{ __('installer::main.install.success') }}
                </div>
                <hr>

                <p>{{ __('installer::main.install.body') }}</p>
            </div>

            <div class="panel-footer text-right">
                <a class="btn btn-link" href="{{ route('installer::configuration') }}">{{ __('pagination.previous') }}</a>
                <button class="btn btn-primary" type="submit">
                    {{ 'Instalar' }}
                </button>
            </div>
        </div>

    </form>

@endsection
