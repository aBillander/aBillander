<div class="tab-pane 
    @if(!empty($view_type) &&  $view_type == 'subscriptions')
        active
    @else
        ''
    @endif"
id="subscriptions_tab">
    <div class="row">
        <div class="col-md-12">
            @component('components.widget')
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('subscriptions_filter_date_range', __('report.date_range') . ':') !!}
                        {!! Form::text('subscriptions_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('sale_pos.partials.subscriptions_table')
        </div>
    </div>
</div>