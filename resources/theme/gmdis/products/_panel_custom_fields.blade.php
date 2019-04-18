
<div class="page-header">
        <h3>
            <span style="color: #dd4814;">Custom Fields</span>
        </h3>        
</div>

        <div class="row">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name_en') ? 'has-error' : '' }}">
                     {{ l('Product Name (English)') }}
                     {!! Form::text('name_en', null, array('class' => 'form-control', 'id' => 'name_en')) !!}
                     {!! $errors->first('name_en', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

         <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('price_usd') ? 'has-error' : '' }}">
                     {{ l('Cost Price (USD Dollar)') }}
                     {!! Form::text('price_usd', null, array('class' => 'form-control', 'id' => 'price_usd', 'autocomplete' => 'off', 
                                      'onclick' => 'this.select()')) !!}
                     {!! $errors->first('price_usd', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('price_usd_conversion_rate') ? 'has-error' : '' }}">
                     {{ l('Exchange rate', 'currencies') }}
                         <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                                    data-content="{{ l('This rate is to be defined according to your Company\'s default currency. For example, if the default currency is the Euro, and this currency is Dollar, type "1.31", since 1€ usually is worth $1.31 (at the time of this writing). Use the converter here for help: http://www.xe.com/ucc/.', 'currencies') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a>
                     {!! Form::text('price_usd_conversion_rate', null, array('class' => 'form-control', 'id' => 'price_usd_conversion_rate')) !!}
                     {!! $errors->first('price_usd_conversion_rate', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>