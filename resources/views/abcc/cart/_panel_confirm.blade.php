<div class="panel panel-primary" id="panel_confirm">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Main Data') }}</h3>
   </div>

   <div class="panel-body">
      <div xclass="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
            {{ l('Shipping Address') }}
            <select class="form-control" id="shipping_address_id" name="shipping_address_id">
               <option value="8" selected="selected">Dirección Principal</option>
            </select>
            
         </div>

         <!-- div class="form-group col-lg-6 col-md-2 col-sm-2 ">
            Forma de pago
                    <div  class="form-control">Domiciliado</div>
            
         </div -->
      </div>
      <div xclass="row">

         <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
            {{ l('Notes') }}
            <textarea class="form-control" id="shipping_conditions" rows="1" name="shipping_conditions" cols="50"></textarea>
            
         </div>
      </div>
   </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-check-circle"></i>
                     &nbsp; {{ l('Place Order') }}
                  </button>
               </div>
</div>