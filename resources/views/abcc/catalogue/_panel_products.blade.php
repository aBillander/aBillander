<div id="panel_products" style="margin-top: -20px">

    <div class="page-header">
        <div class="pull-right" style="padding-top: 4px;">

        </div>
        <h2>
            @if ( count($breadcrumb) )
                <a class="btn btn-sm btn-primary" href="{{ route('abcc.catalogue') }}" title="{{l('Home', 'layouts')}}"><i class="fa fa-home"></i></a>
                <span style="color: #cccccc;">/</span>
                @foreach ($breadcrumb as $val)
                    <span style="color: #dd4814;">{{ $val->name }}</span> <span style="color: #cccccc;">/</span>
                @endforeach
            @else
                <span style="color: #dd4814;">{{ l('Products') }}</span>
            @endif
        </h2>
    </div>


    <div id="div_products">
        <div class="table-responsive">

            @if ($products->count())
                <table id="products" class="table table-hover">
                    <thead>
                    <tr>
                    <!-- th>{{l('ID', [], 'layouts')}}</th -->
                        <th>{{ l('Reference') }}</th>
                        <th>{{ l('EAN Code') }}</th>
                        <th colspan="2">{{ l('Product Name') }}</th>
                        <th>{{ l('Manufacturer') }}</th>
                        <th>
                            @if( \App\Configuration::get( 'ABCC_STOCK_SHOW' ) != 'none')
                                {{ l('Stock') }}
                            @endif
                        </th>
                    <!-- th>{{ l('Measure Unit') }}</th -->

                        <th>
                            <span class="button-pad">{{ l('Customer Price (with Tax)') }}
                                <a href="javascript:void(0);" data-toggle="popover" data-placement="top"
                                   data-container="body" data-trigger="focus"
                                   data-content="
                                        {{ l('Prices are inclusive of Tax') }}
                                   @if($config['enable_ecotaxes'] )
                                           <br/>{{ l('Prices are inclusive of Ecotax') }}
                                   @endif
                                           ">
                                <i class="fa fa-question-circle abi-help"></i>
                                </a>
                            </span>

                            @if( $config['enable_ecotaxes'])
                                <br/><span class="button-pad text-muted">{{ l('Without Ecotax') }}</span>
                            @endif
                        </th>

                        @if (!$config['display_with_taxes'])
                            <th>
                            <span class="button-pad">{{ l('Customer Price') }}
                                <a href="javascript:void(0);" data-toggle="popover" data-placement="top"
                                   data-container="body" data-trigger="focus"
                                   data-content="{{ l('Prices are exclusive of Tax') }}
                                   @if( $config['enable_ecotaxes'] )
                                           <br/>{{ l('Prices are inclusive of Ecotax') }}
                                   @endif
                                           ">
                                <i class="fa fa-question-circle abi-help"></i>
                                </a>
                            </span>
                                @if( $config['enable_ecotaxes'])
                                    <br/><span class="button-pad text-muted">{{ l('Without Ecotax') }}</span>
                                @endif
                            </th>

                            <th>{{ l('Tax') }}</th>
                        @endif

                        <th class="text-right"></th>
                        <th class="text-right"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)

                        <tr>
                        <!-- td>{{ $product->id }}</td -->
                            <td title="{{ $product->id }}">
                                @if ($product->product_type == 'combinable')
                                    <span class="label label-info">{{ l('Combinations') }}</span>
                                @else
                                    {{ $product->reference }}
                                @endif
                            </td>

                            <td>{{ $product->ean13 }}</td>
                            <td>
                                @php
                                    if ($product->img) {
                                        $link_href = URL::to( \App\Image::pathProducts() . $product->img->getImageFolder() . $product->img->id .
                                        '-large_default' . '.' . $product->img->extension );
                                        $caption = $product->img->id . ' ' . $product->img->caption;
                                        $img_href = URL::to( \App\Image::pathProducts() . $product->img->getImageFolder() . $product->img->id .
                                        '-mini_default' . '.' . $product->img->extension ) . '?'. 'time='. time();
                                    }
                                    else {
                                        $link_href = URL::to( \App\Image::pathProducts() . 'default-large_default.png' );
                                        $caption = $product->id . ' ' . $product->name;
                                        $img_href = URL::to( \App\Image::pathProducts() . 'default-mini_default.png' ) . '?'. 'time='. time();
                                    }

                                @endphp

                                <a class="view-image" data-html="false" data-toggle="modal"
                                   href="{{ $link_href }}"
                                   data-title="{{ $product->name }}"
                                   data-caption="({{ $caption }}"
                                   data-content="{{ nl2p($product->description_short) }} <br /> {{ nl2p($product->description) }}"
                                   onClick="return false;" title="{{l('View Image')}}">

                                    <img src="{{ $img_href }}" alt="{{ $product->name }}" style="border: 1px solid #dddddd;">
                                </a>
                            </td>

                            <td>
                                <a class="view-image text-dark" data-html="false" data-toggle="modal"
                                   href="{{ $link_href }}"
                                   data-title="{{ $product->name }}"
                                   data-caption="({{ $caption }}"
                                   data-content="{{ nl2p($product->description_short) }} <br /> {{ nl2p($product->description) }}"
                                   onClick="return false;" title="{{l('View Image')}}">

                                    {{ $product->name }}
                                </a>

                                @if( $config['enable_ecotaxes'] && $product->ecotax )
                                    <br/>
                                    {{ l('Ecotax: ') }} {{ $product->ecotax->name }} ({{ abi_money( $product->ecotax->amount ) }})
                                @endif
                            </td>
                            <td>{{ optional($product->manufacturer)->name }} {{-- optional($product->category)->name --}}</td>
                            <td>
                                @if( \App\Configuration::get( 'ABCC_STOCK_SHOW' ) != 'none')
                                    @if( \App\Configuration::get( 'ABCC_STOCK_SHOW' ) == 'amount')
                                        <div class="progress progress-striped">
                                            <div class="progress-bar progress-bar-{{ $product->stock_badge }}"
                                                 title="{{ l('stock.badge.'.$product->stock_badge, 'abcc/layouts') }}" style="width: 100%">
                                                <span class="badge" style="color: #333333; background-color: #ffffff;">
                                                    {{ $product->as_quantity('quantity_onhand') }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="progress progress-striped" style="width: 34px">
                                            <div class="progress-bar progress-bar-{{ $product->stock_badge }}"
                                                 title="{{ l('stock.badge.'.$product->stock_badge, 'abcc/layouts') }}" style="width: 100%">
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </td>

                            <td>
                                {{ $product->price_tax_inc }}
                                @if( $config['enable_ecotaxes'] && $product->ecotax)
                                    <br/><p class="text-muted">{{ $product->price_tax_inc - $product->getEcotax() }}</p>
                                @endif
                            </td>

                            @if(!$config['display_with_taxes'])
                                <td>
                                    {{ $product->price }}
                                    @if( $config['enable_ecotaxes'] && $product->ecotax)
                                        <br/><p class="text-muted">{{ $product->price - $product->getEcotax() }}</p>
                                    @endif
                                </td>

                                <td>{{$product->tax_percent }}</td>
                            @endif

                            <td>
                                @if ( $product->hasQuantityPriceRules( \Auth::user()->customer ) )
                                    <a class="btn btn-sm btn-custom show-pricerules" href="#" data-target='#myModalShowPriceRules'
                                       data-id="{{ $product->id }}" data-toggle="modal" onClick="return false;"
                                       title="{{ l('Show Special Prices') }} {{-- $product->hasQuantityPriceRules( \Auth::user()->customer ) --}}"><i
                                                class="fa fa-thumbs-o-up"></i>
                                    </a>
                                @endif
                            </td>

                            <td class="text-right xbutton-pad" style="white-space: nowrap;">

                                @if ($product->quantity_onhand > 0 || $product->isManufactured())
                                    <div xclass="form-group">
                                        <div class="input-group" style="width: 72px;">

                                            <input class="input-product-quantity form-control input-sm col-xs-2" data-id="{{$product->id}}"
                                                   type="text" size="5" maxlength="5" style="xwidth: auto;" value="1" onclick="this.select()">

                                            <span class="input-group-btn">
                                              <button class="update-product-quantity btn btn-sm btn-info" data-id="{{$product->id}}" type="button"
                                                      title="{{l('Add to Cart', 'abcc/layouts')}}"
                                                      xonclick="add_discount_to_order($('#order_id').val());">
                                                  <span id="add-icon-{{$product->id}}" class="fa fa-cart-plus"></span>
                                              </button>
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <a class="btn btn-sm btn-warning show-out-of-stock" data-html="false" data-toggle="modal"
                                       href=""
                                       data-content="{{$product->out_of_stock_text}}"
                                       data-title="({{ $product->id }}) [{{$product->reference}}] - {{ $product->name }}"
                                       data-product_id="{{$product->id}}"
                                       data-orders="{{ $product->getOutOfStock() }}"
                                       title="{{l('Click for more Information', 'abcc/layouts')}}"
                                       onClick="return false;"><i class="fa fa-exclamation-triangle"></i> {{l('More Info', 'abcc/layouts')}}</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $products->appends( Request::all() )->render() !!}
                <ul class="pagination">
                    <li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $products->total() ], 'layouts')}} </span>
                    </li>
                </ul>
            @else
                <div class="alert alert-warning alert-block">
                    <i class="fa fa-warning"></i>
                    {{l('No records found', [], 'layouts')}}
                </div>
            @endif
        </div>
    </div>
</div>

@include('abcc.catalogue._modal_view_product')
@include('abcc.catalogue._modal_out_of_stock')
@include('abcc.catalogue._modal_pricerules')


@section('scripts')
    @parent

    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {

            $(document).on('click', '.update-product-quantity', function (evnt) {
                var id = $(this).attr('data-id');
                var quantity = $(this).parent().prev(".input-product-quantity").val();
                var el = $('#add-icon-' + id);
                var elp = $('#add-icon-' + id).parent();

                el.removeClass('fa-cart-plus');
                el.addClass('fa-spinner');

                addProductToCart(id, quantity);

                el.removeClass('fa-spinner');
                el.addClass('fa-check');
                elp.removeClass('btn-info');
                elp.addClass('btn-success');
                return false;

            });


            $(document).on('keydown', '.input-product-quantity', function (evnt) {
                var id = $(this).attr('data-id');
                var quantity = $(this).val();
                var el = $('#add-icon-' + id);
                var elp = $('#add-icon-' + id).parent();

                if (evnt.keyCode == 13) {
                    // console.log("put function call here");
                    evnt.preventDefault();
                    addProductToCart(id, quantity);

                    el.removeClass('fa-cart-plus');
                    el.addClass('fa-check');
                    elp.removeClass('btn-info');
                    elp.addClass('btn-success');
                    return false;
                }
            });

        });


        /* ******************************************************************************************************************************************** */


        function addProductToCart(product_id = 0, quantity = 1) {

            if (product_id <= 0) return;

            var url = "{{ route('abcc.cart.add') }}";
            var token = "{{ csrf_token() }}";

            var payload = {
                product_id: product_id,
                quantity: quantity
            };

            $.ajax({
                url: url,
                headers: {'X-CSRF-TOKEN': token},
                type: 'POST',
                dataType: 'json',
                data: payload,

                success: function (result) {

                    // loadCartlines();

                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    });

                    $('#badge_cart_nbr_items').html(result.cart_nbr_items);

                    // showAlertDivWithDelay("#msg-success");

                    console.log(result);
                }
            });

        }

    </script>

@endsection


@section('styles')
    @parent

    <style>
        /*
        http://twitterbootstrap3buttons.w3masters.nl/?color=%232BA9E1
        https://bootsnipp.com/snippets/M3x9

        */
        .btn-custom {
            color: #fff;
            background-color: #ff0084;
            border-color: #ff0084;
        }

        .btn-custom:hover,
        .btn-custom:focus,
        .btn-custom:active,
        .btn-custom.active {
            background-color: #e60077;
            border-color: #cc006a;
        }

        .btn-custom.disabled:hover,
        .btn-custom.disabled:focus,
        .btn-custom.disabled:active,
        .btn-custom.disabled.active,
        .btn-custom[disabled]:hover,
        .btn-custom[disabled]:focus,
        .btn-custom[disabled]:active,
        .btn-custom[disabled].active,
        fieldset[disabled] .btn-custom:hover,
        fieldset[disabled] .btn-custom:focus,
        fieldset[disabled] .btn-custom:active,
        fieldset[disabled] .btn-custom.active {
            background-color: #ff0084;
            border-color: #ff0084;
        }

        .text-dark {color: #000;}

    </style>

@endsection
