<div class="panel panel-primary" id="panel_product">

   <div class="panel-heading">
      <h3 class="panel-title" data-toggle="collapse" data-target="#header_data" aria-expanded="true">{{ l('') }}</h3>
   </div>

   <div class="panel-body">
{{--
            {!! Form::hidden('customer_id', AbiContext::getContext()->customer->id, array('id' => 'customer_id')) !!}
            {!! Form::hidden('currency_id', AbiContext::getContext()->cart->currency_id, array('id' => 'currency_id')) !!}

            {{ Form::hidden('line_product_id',     null, array('id' => 'line_product_id'    )) }}
            {{ Form::hidden('line_combination_id', null, array('id' => 'line_combination_id')) }}

                  <div class="form-group col-lg-8 col-md-8 col-sm-8">
                     {{ l('Product Name') }}
                     <!-- input class="form-control ui-autocomplete-input" id="line_autoproduct_name" onclick="this.select()" name="line_autoproduct_name" autocomplete="off" value="pan in" type="text" -->

                     {!! Form::text('line_autoproduct_name', null, array('class' => 'form-control', 'id' => 'line_autoproduct_name', 'onclick' => 'this.select()')) !!}


                     {{ Form::hidden( 'line_measure_unit_id', null, ['id' => 'line_measure_unit_id'] ) }}
                  </div>

                 <div class="form-group col-lg-4 col-md-2 col-sm-2" {{ $errors->has('line_quantity') ? 'has-error' : '' }}">
                    {{ l('Quantity') }}
                     {!! Form::text('line_quantity', null, array('class' => 'form-control', 'id' => 'line_quantity', 'xonkeyup' => 'calculate_line_product( )', 'xonchange' => 'calculate_line_product( )', 'onfocus' => 'this.select()', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('line_quantity', '<span class="help-block">:message</span>') !!}

                     {{ Form::hidden('line_quantity_decimal_places', null, array('id' => 'line_quantity_decimal_places')) }}
                 </div>

--}}
   </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <!-- button class="btn btn-success" type="submit" id="product_add_to_cart" xonclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-plus"></i>
                     &nbsp; {{ l('') }}
                  </button -->
               </div>
</div>




