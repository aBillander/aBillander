
@if ( $product )

<div class="panel panel-info">
   <div class="panel-heading">
      <h3 class="panel-title"><strong>WooC</strong> :: [{{ $product['id'] }}] {{ $product['name'] }}  <span class="pull-right">{{ l('slug') }}: {{ $product['slug'] }}</span></h3>
   </div>

{!! Form::open(['route' => ['wproducts.update', $product['sku']], 'id' => 'update_woo_product', 'name' => 'update_woo_product', 'class' => 'form']) !!}

                  
                  {{ csrf_field() }}
                  <input type="hidden" name="_method" value="PUT">

                  <input type="hidden" value="internet" name="abi_product_tab_name" id="abi_product_tab_name">

                  <input type="hidden" id="woo_product_sku"   name="woo_product_sku"  value="{{ $product['sku'] }}" />
                  <input type="hidden" id="product_id_field"  name="product_id_field" value="reference" />

   <div class="panel-body">

        <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="woo_name" class="control-label">{{ l('name') }}</label>
                          {!! Form::text('woo_name', $product['name'], array('class' => 'form-control')) !!}
                      </div>
                  </div>

        </div>

        <div class="row">

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('sku') }}</label>
                          <div class="form-control">{{ $product['sku'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('type') }}</label>
                          <div class="form-control">{{ $product['type'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="status" class="control-label">{{ l('status') }}</label>
                          {!! Form::select('status', $woo_product_statusList, $product['status'], array('class' => 'form-control')) !!}
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="featured" class="control-label">{{ l('featured') }}</label>
                          <div>
                             <div class="radio-inline">
                               <label>
                                 {!! Form::radio('featured', '1', (bool) $product['featured'], ['id' => 'featured_on']) !!}
                                 {!! l('Yes', [], 'layouts') !!}
                               </label>
                             </div>
                             <div class="radio-inline">
                               <label>
                                 {!! Form::radio('featured', '0', ! (bool) $product['featured'], ['id' => 'featured_off']) !!}
                                 {!! l('No', [], 'layouts') !!}
                               </label>
                             </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="catalog_visibility" class="control-label">{{ l('catalog_visibility') }}</label>
                          {!! Form::select('catalog_visibility', $woo_product_catalog_visibilityList, $product['catalog_visibility'], array('class' => 'form-control')) !!}
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('price_html') }}</label>
                          <div class="form-control">{!! $product['price_html'] !!}</div>
                      </div>
                  </div>

        </div>

        <div class="row">

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('tax_status') }}</label>
                          <div class="form-control">{{ $product['tax_status'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('tax_class') }}</label>
                          <div class="form-control">{{ $product['tax_class'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="manage_stock" class="control-label">{{ l('manage_stock') }}</label>
                          <div>
                             <div class="radio-inline">
                               <label>
                                 {!! Form::radio('manage_stock', '1', (bool) $product['manage_stock'], ['id' => 'manage_stock_on']) !!}
                                 {!! l('Yes', [], 'layouts') !!}
                               </label>
                             </div>
                             <div class="radio-inline">
                               <label>
                                 {!! Form::radio('manage_stock', '0', ! (bool) $product['manage_stock'], ['id' => 'manage_stock_off']) !!}
                                 {!! l('No', [], 'layouts') !!}
                               </label>
                             </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="stock_quantity" class="control-label">{{ l('stock_quantity') }}</label>
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                                      data-content="{{ l('Fill only if \'manage_stock\' is set to \'Yes\'.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                          {!! Form::text('stock_quantity', $product['stock_quantity'], array('class' => 'form-control')) !!}
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="stock_status" class="control-label">{{ l('stock_status') }}</label>
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                                      data-content="{{ l('Fill only if \'manage_stock\' is set to \'No\'.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                          {!! Form::select('stock_status', $woo_product_stock_statusList, $product['stock_status'], array('class' => 'form-control')) !!}
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('weight') }}</label>
                          <div class="form-control">{{ $product['weight'] }}</div>
                      </div>
                  </div>

        </div>

        <div class="row">

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('dimensions') }} - {{ l('length') }}</label>
                          <div class="form-control">{{ $product['dimensions']['length'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('dimensions') }} - {{ l('width') }}</label>
                          <div class="form-control">{{ $product['dimensions']['width'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('dimensions') }} - {{ l('height') }}</label>
                          <div class="form-control">{{ $product['dimensions']['height'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('menu_order') }}</label>
                          <div class="form-control">{{ $product['menu_order'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="reviews_allowed" class="control-label">{{ l('reviews_allowed') }}</label>
                          <div>
                             <div class="radio-inline">
                               <label>
                                 {!! Form::radio('reviews_allowed', '1', (bool) $product['manage_stock'], ['id' => 'reviews_allowed_on']) !!}
                                 {!! l('Yes', [], 'layouts') !!}
                               </label>
                             </div>
                             <div class="radio-inline">
                               <label>
                                 {!! Form::radio('reviews_allowed', '0', ! (bool) $product['manage_stock'], ['id' => 'reviews_allowed_off']) !!}
                                 {!! l('No', [], 'layouts') !!}
                               </label>
                             </div>
                          </div>
                      </div>
                  </div>

        </div>

        <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="woo_description" class="control-label">{{ l('description') }}</label>
                          {!! Form::textarea('woo_description', $product['description'], array('class' => 'form-control', 'id' => 'woo_description', 'rows' => '3')) !!}
                      </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="woo_short_description" class="control-label">{{ l('short_description') }}</label>
                          {!! Form::textarea('woo_short_description', $product['short_description'], array('class' => 'form-control', 'id' => 'woo_short_description', 'rows' => '3')) !!}
                      </div>
                  </div>

        </div>

        <div class="row">

                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('images') }}</label>
                          <div xclass="row">

@foreach ( $product['images'] as $image )

                           	<div class="col-md-3 col-md-3 col-sm-3 panel xpanel-default">
                  								<div class="panel-body">
                                        <img width="100%" xheight="32px" src="{{ $image['src'] }}" style="border: 1px solid #dddddd;" alt="{{ $image['alt'] }}">
                  								</div>
                                  <h5 class="text-center">{{ $image['name'] }}</h5>
                            </div>
@endforeach

                          </div>
                      </div>
                  </div>

        </div>

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

{!! Form::close() !!}

@else

<div class="panel panel-info">
   <div class="panel-heading">
      <h3 class="panel-title"><strong>WooC</strong> :: [ - ]   <span class="pull-right">{{ l('slug') }}: - </span></h3>
   </div>
   <div class="panel-body">


<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>


   </div>

@endif