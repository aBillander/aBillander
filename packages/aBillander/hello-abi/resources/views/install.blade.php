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

            @include('installer::partials.notifications')

            @include('installer::partials.errors')

            <div class="panel-body">
                @if ( count($tables) > 0 )
                    <div class="alert alert-danger">
                        {!! __('installer::main.install.database_not_empty') !!}
                    </div>
                @endif

                <p>{!! __('installer::main.install.body') !!}</p>
            </div>

            <div class="panel-footer text-right">
                <a class="btn btn-link" href="{{ route('installer::configuration') }}">{{ __('pagination.previous') }}</a>
                <button class="btn btn-primary" type="submit" onclick="this.disabled=true;">
                    {{ 'Instalar' }}
                </button>
            </div>
        </div>

    </form>

@endsection
