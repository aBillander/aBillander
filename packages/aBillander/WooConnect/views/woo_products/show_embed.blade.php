
@if ( $product )

<div class="panel panel-info">
   <div class="panel-heading">
      <h3 class="panel-title"><strong>WooC</strong> :: [{{ $product['id'] }}] {{ $product['name'] }}  <span class="pull-right">{{ l('slug') }}: {{ $product['slug'] }}</span></h3>
   </div>
   <div class="panel-body">

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
                          <label for="" class="control-label">{{ l('status') }}</label>
                          <div class="form-control">{{ $product['status'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('featured') }}</label>
                          <div class="form-control">{{(int)  $product['featured'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('catalog_visibility') }}</label>
                          <div class="form-control">{{ $product['catalog_visibility'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('price_html') }}</label>
                          <div class="form-control">{!! $product['price_html'] !!}</div>
                      </div>
                  </div>

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
                          <label for="" class="control-label">{{ l('manage_stock') }}</label>
                          <div class="form-control">{{ (int) $product['manage_stock'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('stock_quantity') }}</label>
                          <div class="form-control">{{ $product['stock_quantity'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('stock_status') }}</label>
                          <div class="form-control">{{ $product['stock_status'] ?? '' }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('weight') }}</label>
                          <div class="form-control">{{ $product['weight'] }}</div>
                      </div>
                  </div>

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

        </div>

        <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('description') }}</label>
                          <div class="panel panel-default">
                              <div class="panel-body">
                                {!! $product['description'] !!}
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('short_description') }}</label>
                          <div class="panel panel-default">
                              <div class="panel-body">
                                {!! $product['short_description'] !!}
                              </div>
                          </div>
                      </div>
                  </div>

        </div>

        <div class="row">

                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('images') }}</label>
                          <div xclass="row">

@foreach ( $product['images'] as $image )

                           	<div class="col-md-3 col-md-3 col-sm-3 panel panel-default">
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

@else

<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>

@endif