@extends('installer::layouts.installer')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('panel')

    {{ Form::open(array('url' => route('installer::company'), 'id' => 'create_company', 'name' => 'create_company', 'class' => 'form')) }}
        <div class="panel panel-default" id="panel_generales">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('installer::main.company.title') }}</h3>
            </div>

            @include('installer::partials.notifications')

            @include('installer::partials.company_form')

        </div>
    {{ Form::close() }}

    @include('installer::partials.errors')

@endsection


@include('installer::partials.toggle_password')


@push('scripts')
    <script type="text/javascript">

        $('select[name="state_selector"]').change(function () {

            $('#state_id').val( $('select[name="state_selector"]').val() );

        });

        $('select[name="address[country_id]"]').change(function () {
            var new_countryID = $(this).val();

            populateStatesByCountryID( new_countryID );
        });

        function populateStatesByCountryID( countryID, stateID = 0 )
        {
            $.get('{{ url('/') }}/install/countries/' + countryID + '/getstates', function (states) {

                $('select[name="state_selector"]').empty();
                $('select[name="state_selector"]').append('<option value=0>{{ l('-- Please, select --', [], 'layouts') }}</option>');
                $.each(states, function (key, value) {
                    $('select[name="state_selector"]').append('<option value=' + value.id + '>' + value.name + '</option>');
                });

            }).done( function() {

                $('select[name="state_selector').val(stateID);

                $('#state_id').val(stateID);

            });
        }

        var countryID = $('select[name="address[country_id]"]').val();
        var stateID   = $('#state_id').val();

        // populateStatesByCountryID( countryID, stateID );

    </script>
@endpush
