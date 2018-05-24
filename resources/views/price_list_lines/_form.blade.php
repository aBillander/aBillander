
              {!! Form::hidden('base_price', $price->product->price, array('id' => 'base_price')) !!}
              {!! Form::hidden('tax_id', $price->product->tax->id, array('id' => 'tax_id')) !!}
<div class="row">
          <div class="form-group col-lg-4 col-md-4 col-sm-4">
             {!! Form::label('cost_price', l('Cost Price')) !!}
             {!! Form::text('cost_price', $price->product->cost_price, array('class' => 'form-control', 'id' => 'cost_price', 'autocomplete' => 'off', 
                              'onfocus' => 'this.blur()', 'onclick' => 'this.select()', 'onkeyup' => 'new_margin()', 'onchange' => 'new_margin()')) !!}
          </div>
          <div class="form-group col-lg-4 col-md-4 col-sm-4">
             {!! Form::label('price', l('Sales Price')) !!}
             {!! Form::text('price', null, array('class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off', 
                              'onclick' => 'this.select()', 'onkeyup' => 'new_margin()', 'onchange' => 'new_margin()')) !!}
          </div>
          <div class="form-group col-lg-4 col-md-4 col-sm-4">
             {!! Form::label('price_tax_inc', l('Sales Price (with Tax)')) !!}
             {!! Form::text('price_tax_inc', null, array('class' => 'form-control', 'id' => 'price_tax_inc', 'autocomplete' => 'off', 
                              'onclick' => 'this.select()', 'onkeyup' => 'new_margin_price()', 'onchange' => 'new_margin_price()')) !!}
          </div>
</div>

<div class="row">
          <div class="form-group col-lg-4 col-md-4 col-sm-4">
             {!! Form::label('discount', l('Discount (%)')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Discount on Product Price', [], 'layouts') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
             {!! Form::text('discount', null, array('class' => 'form-control', 'id' => 'discount', 'autocomplete' => 'off', 
                              'onclick' => 'this.select()', 'onkeyup' => 'apply_discount()', 'onchange' => 'apply_discount()')) !!}
          </div>
          <div class="form-group col-lg-4 col-md-4 col-sm-4">
             {!! Form::label('margin', l('Margin (%)')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ \App\Configuration::get('MARGIN_METHOD') == 'CST' ?
                                l('Margin calculation is based on Cost Price', [], 'layouts') :
                                l('Margin calculation is based on Sales Price', [], 'layouts') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
             {!! Form::text('margin', null, array('class' => 'form-control', 'id' => 'margin', 'autocomplete' => 'off', 
                              'onclick' => 'this.select()', 'onkeyup' => 'new_price()', 'onchange' => 'new_price()')) !!}
          </div>
</div>


        <?php if (!isset($back_route)) $back_route = ''; ?>
        <input type="hidden" name="back_route" value="{{$back_route}}"/>

{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to( ($back_route != '' ? $back_route : '404') , l('Cancel', [], 'layouts'), array('class' => 'btn btn-warning')) !!}
