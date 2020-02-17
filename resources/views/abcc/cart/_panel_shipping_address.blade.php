<div class="panel panel-success" id="panel_confirm">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Shipping Address') }} [{{ $cart->shipping_address_id }}]</h3>
   </div>

{!! Form::open(array('route' => 'abcc.cart.shippingaddress.store', 'id' => 'update_shipping_address', 'name' => 'update_shipping_address', 'class' => 'form')) !!}

   <div class="panel-body">
      <div xclass="row">

{{--
         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            {!! Form::select('shipping_address_id', Auth::user()->getAllowedAddressList(), old('shipping_address_id', Auth::user()->address_id ?: Auth::user()->customer->shipping_address_id), array('class' => 'form-control', 'id' => 'shipping_address_id')) !!}
            {!! $errors->first('shipping_address_id', '<span class="help-block">:message</span>') !!}
         </div>
--}}

         <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            <div class="form-group drop-down-list">
                <!-- label for="test3">Select a value</label -->
                <input id="shipping_address_alias" readonly class="form-control" value="{{  old('shipping_address_alias', $cart->shippingaddress->alias)  }}" style="background-color: white;">
                {{-- readonly prevents borowser autocomplete! --}}
                <span class="ddl-caret" style="background-color: #e7e7e7;border-top-right-radius: 4px;
border-bottom-right-radius: 4px; border: 1px solid #cccccc; height: 38px;"></span>
                <ul class="dropdown-menu">

@foreach ( Auth::user()->getAllowedAddresses() as $address )

                    <li data-text="{{ $address->alias }}" data-id="{{ $address->id }}">
                     <a>
                        <b>{{ $address->alias }}</b>
                        <div> &nbsp; {{ $address->address1 }}</div>
                        <div> &nbsp; {{ $address->city }}, {{ $address->postcode }} {{ $address->state->name }}</div>
                     </a>
                  </li>
@endforeach                    
                </ul>

                <input type="hidden" id="shipping_address_id" name="shipping_address_id" value="{{  old('shipping_address_id', $cart->shippingaddress->id)  }}" class="form-control-id">
            </div>
         </div>


         <!-- div class="form-group col-lg-6 col-md-2 col-sm-2 ">
            Forma de pago
                    <div  class="form-control">Domiciliado</div>
            
         </div -->
      </div>
   </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">

@if ( Auth::user()->getAllowedAddresses()->count() > 1 )
                  
                  <button class="btn btn-grey" type="button" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-truck"></i>
                     &nbsp; {{ l('Update', 'layouts') }}
                  </button>
@endif

               </div>

{!! Form::close() !!}

</div>



{{-- Bootstrap Dropdown Select Replacement Plugin - DDL
   https://www.jqueryscript.net/form/Bootstrap-Dropdown-Replacement-Plugin-DDL.html
--}}

@section('scripts')     @parent
<script type="text/javascript">
   
   {{-- Gorrino Include --}}
   {!! file_get_contents( resource_path() . '/views/abcc/cart/bootstrap-ddl/bootstrap-ddl.js'); !!}



  $(document).ready(function() {

        $(document).on('change', '#shipping_address_alias', function(evnt) {

            $("#update_shipping_address").submit();

        });

  }); // $(document).ready

</script>
@endsection


@section('styles')    @parent

<style>
   
   {!! file_get_contents( resource_path() . '/views/abcc/cart/bootstrap-ddl/bootstrap-ddl.css'); !!}

</style>

@endsection
