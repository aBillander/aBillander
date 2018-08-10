
          <div class="panel panel-default">
          <div class="panel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <br />
                    <span class="badge" style="background-color: #3a87ad;" title="{{ $list->currency->name }}">{{ $list->currency->iso_code }}</span>
                        <br />
                    <span class="label label-success">{{ $list->getType() }}</span>
                    @if ($list->type != 'price')
                      <span class="label label-default">{{ $list->as_percent('amount') }}%</span>
                    @endif
                    @if ( $list->price_is_tax_inc )
                        <br />
                        <span class="label label-info">{{ l('Tax Included', [], 'pricelists') }}</span>
                    @endif
            <br />
            <br />

          </div>
          </div>
