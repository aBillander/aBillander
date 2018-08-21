
          <div class="panel panel-default">
          <div class="panel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <br />
                    <!-- span class="badge" style="background-color: #3a87ad;" title="{ { $list->currency->name } }">{ { $list->currency->iso_code }} </span>
                        <br / -->
                    <span class="label label-success" title=" [{{ $list->warehouse->alias }}] {{ $list->warehouse->name }} ">[{{ $list->warehouse->alias }}] {{ $list->warehouse->name }}</span>
                    @if ($list->initial_inventory)
                      <span class="label label-default">{{l('Initial Inventory')}}</span>
                    @endif
            <br />
            <br />

          </div>
          </div>
