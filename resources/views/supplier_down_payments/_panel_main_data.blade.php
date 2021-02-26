
            <div class="panel panel-primary" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }}</h3>
               </div>

              <div class="panel-body">

                @include('errors.list')

                {!! Form::model($downpayment, array('method' => 'PATCH', 'route' => array('supplier.downpayments.update', $downpayment->id))) !!}

{{--  Avoid accidental modifications:

                  {{ Form::hidden('supplier_id',       null, ['id' => 'supplier_id']      ) }}
                  {{ Form::hidden('supplier_order_id', null, ['id' => 'supplier_order_id']) }}
--}}
                  @include('supplier_down_payments._form_with_document')

                {!! Form::close() !!}
              </div>

            </div>
