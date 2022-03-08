
{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="" name="tab_name" id="tab_name_purchases">

<div class="panel panel-primary" id="panel_purchases">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Purchases') }}</h3>
   </div>
   <div class="panel-body">

<!-- Purchases Prices -->

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('supply_lead_time') ? 'has-error' : '' }}">
                     {{ l('Lead Time') }}
                     {!! Form::text('supply_lead_time', null, array('class' => 'form-control', 'id' => 'supply_lead_time')) !!}
                     {!! $errors->first('supply_lead_time', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('supplier_reference') ? 'has-error' : '' }}">
                     {{ l('Supplier Reference') }}
                     {!! Form::text('supplier_reference', null, array('class' => 'form-control', 'id' => 'supplier_reference')) !!}
                     {!! $errors->first('supplier_reference', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('cost_price') ? 'has-error' : '' }}">
                     {{ l('Cost Price') }}
                     {!! Form::text('cost_price', null, array('class' => 'form-control', 'id' => 'cost_price')) !!}
                     {!! $errors->first('cost_price', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     {{ l('Average Cost Price') }}
                     {!! Form::text('cost_average', null, array('class' => 'form-control', 'id' => 'cost_average', 'onfocus' => 'this.blur()')) !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     {{ l('Last Purchase Price') }}
                     {!! Form::text('last_purchase_price', null, array('class' => 'form-control', 'id' => 'last_purchase_price', 'onfocus' => 'this.blur()')) !!}
                  </div>
        </div>

        <div class="row">
        </div>

        <div class="row">
        </div>

<!-- Purchases Prices ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;$('#tab_name_purchases').val('purchases');this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}
