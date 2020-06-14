

<div class="panel panel-primary" id="panel_webshop">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Web Shop') }}</h3>
   </div>

<!-- Internet -->
{{--
<div class=" hidden " id="wooc_match_ko">

{ {-- !! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !! --} }
<form id="publish-product-form" action="{{ route('wproducts.store') }}" method="POST">
  {{ csrf_field() }}
  {!! Form::hidden('abi_product_id', $product->id, array('id' => 'abi_product_id')) !!}

<input type="hidden" value="webshop" name="tab_name" id="tab_name">

   <div class="panel-body">

        <div class=" hidden content_product_data loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}</div>

        <div class="row">

                   <div class=" hidden form-group col-lg-2 col-md-2 col-sm-2" id="div-publish_to_web">
                     {!! Form::label('publish_to_web', l('Publish to web?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('publish_to_web', '1', true, ['id' => 'publish_to_web_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('publish_to_web', '0', false, ['id' => 'publish_to_web_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                  <!-- div class="col-lg-3 col-md-3 col-sm-3">
                      <div class="form-group">
                          {!! Form::label('webshop_id', l('Webshop ID'), ['class' => 'control-label']) !!}
                          <div class="col-lg-6 col-md-6 col-sm-6">
                            <div id="webshop_id" class="form-control">{{ $product->reference }}</div>
                          </div>
                      </div>
                  </div -->

                  <div class=" hidden col-lg-3 col-md-3 col-sm-3">
                      <div class="form-group {{ $errors->has('webshop_id') ? 'has-error' : '' }}">
                          {!! Form::label('webshop_id', l('Webshop ID'), ['class' => 'control-label']) !!}
                          <div class="col-lg-6 col-md-6 col-sm-6">
                            {!! Form::text('webshop_id', null, array('class' => 'form-control', 'id' => 'webshop_id')) !!}
                            {!! $errors->first('webshop_id', '<span class="help-block">:message</span>') !!}
                          </div>
                      </div>
                  </div>


                  <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('description_short') ? 'has-error' : '' }}">
                     {!! Form::label('description_short', l('Short Description'), ['class' => 'control-label']) !!}
                     {!! Form::textarea('description_short', $product->description_short, array('class' => 'form-control', 'id' => 'description_short', 'rows' => '3')) !!}
                     {!! $errors->first('description_short', '<span class="help-block">:message</span>') !!}
                  </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-publish_to_web_1">

                        <a class="btn xbtn-sm btn-lightblue" href="javascript:void(0);"
                                onclick="event.preventDefault();
                                         document.getElementById('publish-product-form').submit();" title="{{l('Publish', [], 'layouts')}}"><i class="fa fa-cloud-upload"></i> {{l('Publish', [], 'layouts')}}</a>
{ {-- This has replaced main form
                        <form id="publish-product-form" action="{{ route('wproducts.store') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            {!! Form::hidden('abi_product_id', $product->id, array('id' => 'abi_product_id')) !!}
                        </form>
--} }

                   </div>

        </div>

        <div class="row">
        </div>

        <div class="row">
        </div>

   </div>
{ {--
   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>
--} }
{!! Form::close() !!}

</div>

<div class=" hidden " id="wooc_match_ok">

   <div class="panel-body">

        <div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2">

                        <a class="btn xbtn-sm alert-info view-webshop-data" href="javascript::void(0);" title="{{l('View', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('View Data', [], 'layouts')}}</a>

                   </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2">

                      <a class="btn xbtn-sm btn-blue" href="{{ URL::route('wproducts.fetch', $product->reference ) }}" title="{{l('Fetch', [], 'layouts')}}" target="_blank"><i class="fa fa-eyedropper"></i> {{l('Fetch Data', [], 'layouts')}}</a>

                   </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2">

                      <a class="btn xbtn-sm btn-grey" href="{{ URL::route('wproducts.import.product.images', ['product_sku' => $product->reference] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-image"></i> {{l('Import Images')}}</a>

                   </div>

                   <div class="form-group col-lg-3 col-md-3 col-sm-3">

                      <a class="btn xbtn-sm btn-grey" href="{{ URL::route('wproducts.import.product.descriptions', ['product_sku' => $product->reference] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-file-text-o"></i> {{l('Import Descriptions')}}</a>

                   </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2">

                      <div class="btn-group">
                        <a href="javascript::void();" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-refresh"></i> {{l('Update', [], 'layouts')}}</a>
                        <a href="javascript::void();" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                          <li><a href="{{ route('wproducts.update.product.stock', $product->reference ) }}"><i class="fa fa-th"></i> &nbsp;{{l('Update Stock')}}</a></li>
                          <li><a href="{{ route('wproducts.update.product.price', ['product_sku' => $product->reference] ) }}"><i class="fa fa-money"></i> &nbsp;{{l('Update Price')}}</a></li>
                          <!-- li class="divider"></li -->
                        </ul>
                      </div>

                   </div>


        </div>

   </div>


</div>
--}}
<!-- Internet ENDS -->

</div>



<div id="customer-webshop-data">

    <div id="customer-webshop-data-content"></div>

</div>




@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {

          $(document).on('click', '.view-webshop-data', function(evnt) 
          {

              getCustomerWebShopEmbedData();

          });

{{--
        $(document).on('click', '.create-pricerule', function(evnt) {

            // Initialize
            $('#product_query').val('');
            $('#reference').val('');
            $('#product_id').val('');
            $('#rule_price').val('');
            $('#rule_currency_id').val("{{ \App\Configuration::getInt('DEF_CURRENCY')}}");
            $('#from_quantity').val('1');
            $('#date_from_form').val('');
            $('#date_to_form').val('');

            // Open popup
            $('#priceruleModal').modal({show: true});
            $("#product_query").focus();


            return false;
        });
--}}
      

   });

    function getCustomerWebShopEmbedData( pId = 0 )
    {           
       var panel = $("#customer-webshop-data");
       var url = "{{ route('wcustomers.show', [(int) $customer->webshop_id, 'embed']) }}";

      // if ( pId <= 0 ) return;
      // if ( {{ (int) $customer->webshop_id }} <= 0 ) return;

       panel.html(" &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}").addClass('loading');

       $.get(url, {}, function(result){

            if ( result.success == 'OK')
            {
                //
                $("#wooc_match_ok").removeClass('hidden');
            } else {
                // 
                $("#wooc_match_ko").removeClass('hidden');
            }

             panel.html(result.html);
             panel.removeClass('loading');

             $("[data-toggle=popover]").popover();

       }, 'json').done( function(result){

            // var selector = "#line_autoproduct_name";
            // var next = $('#next_line_sort_order').val();

            // $('#modal_document_line').modal({show: true});
            // $("#line_autoproduct_name").focus();

        });

      return false;              
    }

{{--

      $(window).on('hashchange',function(){
      page = window.location.hash.replace('#','');
      if (page == 'pricerules') getCustomerPriceRules(page);
    });

    $(document).on('click','.pagination_pricerules a', function(e){
      e.preventDefault();
      var stubs;
      var page;

      stubs = $(this).attr('href').split('page=');
      page = stubs[ stubs.length - 1 ]; // Like a BOSS!!!!

      // if (page = 'pricerules') getCustomerPriceRules(page);
      getCustomerPriceRules(page);
      // location.hash = page;
    });

    function getProductInternetData( pId = 0 ){

      // $('.content_product_data').html(pId);
      return;

      if ( pId <= 0 ) return;

      $.ajax({
        url: '{{ route( 'wproducts.fetch', ':pId' ) }}'.replace(":pId", pId),
        data: {
          items_per_page_pricerules: 23
        }
      }).done(function(data){
        $('.content_product_data').html(data);
        
        $('.content_product_data').removeClass('loading');
      });
    }

    $(document).on('keydown','.items_per_page_pricerules', function(e){
  
      if (e.keyCode == 13) {
       // console.log("put function call here");
       e.preventDefault();
       getCustomerPriceRules();
       return false;
      }

    });
--}}

</script>

@endsection


@section('styles')    @parent

<style>

  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }

</style>

@endsection

