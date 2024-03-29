
          <div class="panel panel-default">
          <div class="panel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <br />
                    <!-- span class="badge" style="background-color: #3a87ad;" title="{ { $list->currency->name } }">{ { $list->currency->iso_code }} </span>
                        <br / -->
                    <span class="badge" style="background-color: #3a87ad;"> {{ $list->warehouse->alias }} </span> {{ $list->warehouse->name }}</span>
                        <br />
                        <br />
                    @if ($list->initial_inventory)
                      <span class="label label-success">{{ l('Initial Inventory') }}</span>
                    @else
                      <span class="label label-success">{{ l('Stock Adjustment') }}</span>
                    @endif
            <br />
            <br />

@if ( !$list->processed )
                <p class="text-center">
                    <a class="btn btn-xs btn-warning" href="{{ URL::to('stockcounts/' . $list->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i> &nbsp;{{l('Edit', [], 'layouts')}}</a>
                </p>
@endif

          </div>
          </div>
