
@if ( $category )

<div class="panel panel-info">
   <div class="panel-heading">
      <h3 class="panel-title"><strong>WooC</strong> :: [{{ $category['id'] }}] {{ $category['name'] }}  <span class="pull-right">{{ l('slug') }}: {{ $category['slug'] }}</span></h3>
   </div>
   <div class="panel-body">

        <div class="row">

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('parent') }}</label>
                          <div class="form-control">{{ $category['parent'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('display') }}</label>
                          <div class="form-control">{{ $category['display'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('menu_order') }}</label>
                          <div class="form-control">{{ $category['menu_order'] }}</div>
                      </div>
                  </div>


                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('count') }}</label>
                          <div class="form-control">{{ $category['count'] }}</div>
                      </div>
                  </div>

        </div>

        <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('description') }}</label>
                          <div class="panel panel-default">
                              <div class="panel-body">
                                {!! $category['description'] !!}
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('image') }}</label>
                          <div class="panel panel-default">
                                <div class="panel-body">
                                      <img width="100%" xheight="32px" src="{{ optional($category['image'])['src'] }}" style="border: 1px solid #dddddd;" alt="{{ optional($category['image'])['alt'] }}">
                                </div>
                                  <h5 class="text-center">{{ optional($category['image'])['name'] }}</h5>
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