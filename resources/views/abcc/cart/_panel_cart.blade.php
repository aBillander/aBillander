<div id="panel_cart"> 

<div class="panel panel-primary" id="panel_cart">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('') }}{{ l('Cart Items') }}

        <span class="badge pull-right" style="background-color: #3a87ad; color: #ffffff;" title="{{ $cart->currency->name }}">{{ $cart->currency->iso_code }}</span> &nbsp; 

         @if($cart->customer->sales_equalization)
            <span id="sales_equalization_badge" class="badge pull-right" style="xbackground-color: #3a87ad; margin-right: 18px;" title="{{ l('Equalization Tax') }}"> {{ l('SE') }} </span>
         @endif
      </h3>
   </div>

   <div id="header_data" style="">


   <div class="panel-body">

        <div id="msg-success" class="alert alert-success alert-block" style="display:none;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span id="msg-success-counter" class="badge"></span>
          <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
        </div>

        <div id="msg-success-delete" class="alert alert-success alert-block" style="display:none;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span id="msg-success-delete-counter" class="badge"></span>
          <strong>{!!  l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
        </div>


<div id="msg-success-update" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-update-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

        <div id="panel_cart_lines" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
          
        {{--  @ include('abcc.cart._panel_cart_lines.blade.php') --}}

        </div>

    </div><!-- div class="panel-body" -->

               <!-- div class="panel-footer text-right">
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; Guardar
                  </button>
               </div -->

<!-- Order header ENDS -->

   </div>

   <div class="panel-footer text-right">       </div>

</div>


    

<!-- div id="panel_cart_total" class="">
  
    @ include('abcc.cart._panel_cart_total')

</div -->


</div>


@include('abcc.catalogue._modal_view_product')

@include('abcc.cart._modal_cart_line_delete')

@include('abcc.cart._modal_confirm_submit')
