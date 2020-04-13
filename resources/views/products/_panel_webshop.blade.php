

<div class="panel panel-primary" id="panel_internet">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Web Shop') }}</h3>
   </div>

<!-- Internet -->

{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="internet" name="tab_name" id="tab_name">

   <div class="panel-body">

        <div class=" hidden content_product_data loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}</div>

        <div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-publish_to_web">
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

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-publish_to_web">

                <a class="btn xbtn-sm btn-blue" href="{{ URL::route('wproducts.fetch', $product->reference ) }}" title="{{l('Fetch', [], 'layouts')}}" target="_blank"><i class="fa fa-eyedropper"></i> {{l('Fetch Data', [], 'layouts')}}</a>


                   </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-publish_to_web">

                <a class="btn xbtn-sm btn-grey" href="{{ URL::route('wproducts.import.product.images', ['product_sku' => $product->reference] ) }}" title="{{l('Import', [], 'layouts')}}" target="_blank"><i class="fa fa-image"></i> {{l('Import Images')}}</a>


                   </div>
        </div>

        <hr />

        <div class="row">
                  <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('description_short') ? 'has-error' : '' }}">
                     {{ l('Short Description') }}
                     {!! Form::textarea('description_short', null, array('class' => 'form-control', 'id' => 'description_short', 'rows' => '3')) !!}
                     {!! $errors->first('description_short', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
        </div>

        <div class="row">
        </div>

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

{!! Form::close() !!}

<!-- Internet ENDS -->

</div>


@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {


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
      

   });

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


</script>

@endsection


@section('styles')    @parent

<style>

  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }

</style>

@endsection

