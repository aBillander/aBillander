@extends('layouts.app')
@section('title', __('lang_v1.calendar'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'lang_v1.calendar' )</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-sm-3">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        @if(!empty($users))
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('user_id', __('role.user') . ':') !!}
                                    {!! Form::select('user_id', $users, auth()->user()->id, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('location_id', __('sale.location') . ':') !!}
                                {!! Form::select('location_id', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        @foreach($event_types as $key => $value)
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                      {!! Form::checkbox('events', $key, true, 
                                      [ 'class' => 'input-icheck event_check']); !!} <span style="color: {{$value['color']}}">{{ $value['label'] }}</span>
                                    </label>
                            </div>
                        </div>
                        @endforeach
                        @if(Module::has('Essentials'))
                        <div class="col-md-12">
                            <button class="btn btn-block btn-success btn-modal" 
                                data-href="{{action('\Modules\Essentials\Http\Controllers\ToDoController@create')}}?from_calendar=true" 
                                data-container="#task_modal">
                                <i class="fa fa-plus"></i> @lang( 'essentials::lang.add_to_do' )</a>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')
    
    <script type="text/javascript">
        $(document).ready(function(){
            var events = [];
            $.each($("input[name='events']:checked"), function(){
                events.push($(this).val());
            });

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next,today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek'
                },
                contentHeight: 'auto',
                eventLimit: 2,
                eventSources: [
                    {
                        url: '/calendar', 
                        type: 'get',
                        data: {
                            events: events
                        }
                         
                    }
                ] ,
                eventRender: function (event, element) {
                    if (event.title_html) {
                        element.find('.fc-title').html(event.title_html);
                    }
                    if (event.event_url) {
                        element.attr('href', event.event_url);
                    }
                }
            });
        });

        $(document).on('change', '#user_id, #location_id', function(){
            reload_calendar();
        });

        $(document).on('ifChanged', '.event_check', function(){
            reload_calendar();
        }) 

        function reload_calendar(){
            data = [];
            if($('select#location_id').length) {
                data.location_id = $('select#location_id').val();
            }
            if($('select#user_id').length) {
                data.user_id = $('select#user_id').val();
            }

            var events = [];
            $.each($("input[name='events']:checked"), function(){
                events.push($(this).val());
            });

            data.events = events;

            var events_source = {
                url: '/calendar',
                type: 'get',
                data: data
            }
            $('#calendar').fullCalendar( 'removeEventSource', events_source);
            $('#calendar').fullCalendar( 'addEventSource', events_source);
        }
    </script>
@endsection
