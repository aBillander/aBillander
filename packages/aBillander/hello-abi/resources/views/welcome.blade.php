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
                <p>{{ __('installer::main.welcome.body') }}</p>
                <hr>
                <div class="form-group col-sm-12 col-md-6">
                    <p class="lead">{{ __('installer::main.welcome.select_lang') }}</p>
                    <select class="form-control" name="lang" id="lang">
                        @foreach(config()->get('installer.supportedLocales') as $localeCode => $properties)
                            <option value="{{ $localeCode }}" {{ $language == $localeCode ? 'selected' : '' }}>
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

@push('scripts')
<script>
    $(function(){
      // bind change event to select
      $('#lang').on('change', function () {
          // var url = $(this).val(); // get selected value
          var url = "{{ route('installer::welcome') }}?lang=" + $(this).val();
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });
</script>
@endpush
