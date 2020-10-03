@extends('installer::layouts.installer')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@push('styles')
    <style>
        .terms {
            border: 1px solid #ddd;
            height: 200px;
            overflow: auto;
            padding: 10px;
            margin: 30px 0 15px;
        }
        input[type='checkbox'] {
            position: relative;
            top: 1px;
            margin-right: 5px;
        }
    </style>
@endpush

@section('panel')

    <form class="" action="{{ route('installer::license') }}" method="post">
        {{ csrf_field() }}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('installer::main.license.title') }}</h3>
            </div>

            <div class="panel-body">
                <p>{{ __('installer::main.license.body') }}</p>

                <div class="terms">
                    {!! __('installer::main.license.license') !!}
                </div>
                <label>
                    <input type="checkbox" name="terms" value="1" required>
                    {{ __('installer::main.license.accept') }}
                </label>

                @include('installer::partials.errors')
            </div>

            <div class="panel-footer text-right">
                <a class="btn btn-link" href="{{ route('installer::welcome') }}">{{ __('pagination.previous') }}</a>
                <button class="btn btn-primary" type="submit">
                    {{ __('pagination.next') }}
                </button>
            </div>
        </div>

    </form>

@endsection
