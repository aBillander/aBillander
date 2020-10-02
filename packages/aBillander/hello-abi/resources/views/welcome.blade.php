@extends('installer::layouts.installer')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('panel')

    <form class="" action="{{ route('installer::welcome') }}" method="post">
        {{ csrf_field() }}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('installer::main.welcome.title') }}</h3>
            </div>

            <div class="panel-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <hr>
                <div class="form-group col-sm-12 col-md-6">
                    <p class="lead">{{ __('installer::main.welcome.select_lang') }}</p>
                    <select class="form-control" name="lang">
                        @foreach(config()->get('installer.supportedLocales') as $localeCode => $properties)
                            <option value="{{ $localeCode }}" {{ app()->getLocale() == $localeCode ? 'selected' : '' }}>
                                {{ $properties['native'] }} ({{ $properties['name'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="panel-footer text-right">
                <button class="btn btn-primary" type="submit">
                    {{ __('pagination.next') }}
                </button>
            </div>
        </div>

    </form>

@endsection
