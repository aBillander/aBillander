


            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Price List') }}
                    <span class="badge pull-right" style="background-color: #3a87ad;" title="{{ $list->currency->name }}">{{ $list->currency->iso_code }}</span>
                </h4>
              </li>

                  <li class="list-group-item">
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

                <p class="text-center">
                    <a class="btn btn-xs btn-warning" href="{{ URL::to('pricelists/' . $list->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i> &nbsp;{{l('Edit', [], 'layouts')}}</a>
                </p>
                    
                  </li>

            </ul>

