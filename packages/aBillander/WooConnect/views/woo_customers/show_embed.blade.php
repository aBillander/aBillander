
@if ( $customer )

<div class="panel panel-info">
   <div class="panel-heading">
      <h3 class="panel-title"><strong>WooC</strong> :: [{{ $customer['id'] }}] {{ $customer['first_name'] }} {{ $customer['last_name'] }}  <span class="pull-right">{{ l('date_created') }}: {{ $customer['date_created'] }}</span></h3>
   </div>
   <div class="panel-body">

      {!! '<pre>'.print_r($customer, true).'</pre> ' !!}

{{--
        <div class="row">

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('parent') }}</label>
                          <div class="form-control">{{ $customer['parent'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('display') }}</label>
                          <div class="form-control">{{ $customer['display'] }}</div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('menu_order') }}</label>
                          <div class="form-control">{{ $customer['menu_order'] }}</div>
                      </div>
                  </div>


                  <div class="col-lg-2 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('count') }}</label>
                          <div class="form-control">{{ $customer['count'] }}</div>
                      </div>
                  </div>

        </div>

        <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('description') }}</label>
                          <div class="panel panel-default">
                              <div class="panel-body">
                                {!! $customer['description'] !!}
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="" class="control-label">{{ l('image') }}</label>
                          <div class="panel panel-default">
                                <div class="panel-body">
                                      <img width="100%" xheight="32px" src="{{ optional($customer['image'])['src'] }}" style="border: 1px solid #dddddd;" alt="{{ optional($customer['image'])['alt'] }}">
                                </div>
                                  <h5 class="text-center">{{ optional($customer['image'])['name'] }}</h5>
                          </div>
                      </div>
                  </div>

        </div>
--}}
   </div>

@else

<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>

@endif