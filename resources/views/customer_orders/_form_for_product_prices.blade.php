
<div class="xpanel-body well" style="margin-top: 10px;margin-bottom: 20px;">

<div class="row">

         <div class="col-lg-12 col-md-12 col-sm-12 ">

                  @include('customer_orders._chunck_stock')
            
         </div>

</div>


</div><!-- div class="xpanel-body well" style="margin-top: 10px" ENDS -->


<div class="xpanel-body well" style="margin-top: 10px;margin-bottom: 20px;">

<div class="row">

         <div class="col-lg-12 col-md-12 col-sm-12 ">

					        @include('customer_quotations._chunck_prices_rules')
            
         </div>

</div>


</div><!-- div class="xpanel-body well" style="margin-top: 10px" ENDS -->
<div class="xpanel-body well" style="margin-top: 10px;margin-bottom: 20px;">

<div class="row">

         <div class="col-lg-12 col-md-12 col-sm-12 ">

					        @include('customer_quotations._chunck_prices_recent_sales')
            
         </div>

</div>


</div><!-- div class="xpanel-body well" style="margin-top: 10px" ENDS -->
<div class="xpanel-body well" style="margin-top: 10px;margin-bottom: 20px;">

<div class="row">

         <div class="col-lg-12 col-md-12 col-sm-12 ">

                  <!-- h4>
                      <span style="color: #dd4814;">{{ l('Prices by Price List') }}</span> < ! - - span style="color: #cccccc;">/</span>  - - >
                       
                  </h4 -->

                       @include('customer_quotations._chunck_prices_by_pricelist')
            
         </div>
{{--
         <div class="col-lg-6 col-md-6 col-sm-6 ">

                       @include('customer_quotations._chunck_prices_rules')
            
         </div>
--}}
</div>


</div><!-- div class="xpanel-body well" style="margin-top: 10px" ENDS -->
