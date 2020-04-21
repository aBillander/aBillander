
        <div class="form-group">
            {{ l('Customer') }}:
            <div class="input-group">
                <div class="form-control">{{ $order['customer_id'] }} - {{ $address['first_name'].' '.$address['last_name'] }}</div>
@if ( $customer )                
                <span class="input-group-btn">
                    <a href="{{ URL::to('customers/' . $customer->id) }}" class="btn btn-success" title="{{ l('Go to Customer') }}" target="_blank">
                        <span class="fa fa-eye"></span>
                    </a>
                </span>
@else               
                <span class="input-group-addon" title="{{ l('This Customer has not been imported') }}">
                    <span class="fa fa-eye-slash"></span>
                </span>
@endif                
            </div>
        </div>
        
        <div class="form-group">
            {{ l('Company') }}:
            <div class="form-control">{{ $address['company'] }}</div>
        </div>
        
        <div class="form-group">
            {{ l('Address') }}:
            <div class="form-control" style="height: auto !important;">
                <span 
                    @if( $address["address_1"] != $order['billing']["address_1"] )
                        class="text-primary"
                    @endif
                >{{ $address["address_1"] }}</span>
            @if ( isset($address["address_2"]) && ($address["address_2"] != '') )
                <br />
                <span 
                    @if( $address["address_2"] != $order['billing']["address_2"] )
                        class="text-primary"
                    @endif
                >{{ $address["address_2"] }}</span>
            @endif
            </div>
        </div>
        
        <!-- div class="form-group">
            {{ l('VAT Number') }}
            <div class="form-control">{ { $address['vat_number'] } }</div>
        </div -->
        
        <!-- div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa fa-envelope"></span>
                </span>
                <div class="form-control">{ { $address['email'] } }</div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa fa-phone"></span>
                </span>
                <div class="form-control">{ { $address['phone'] } }</div>
            </div>
        </div -->
        
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa fa-envelope"></span>
                </span>
                <div class="form-control" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">{{ $address['email'] ?? '' }}</div>

                <span class="input-group-addon" style="padding: 8px 6px;
background-color: white;
border: 0px solid;"></span>

                <span class="input-group-addon" style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;">
                    <span class="fa fa-phone"></span>
                </span>
                <div class="form-control">{{ $address['phone'] ?? '' }}</div>
            </div>
        </div>
        
        <div class="form-group">
            {{ l('City') }}:
            <div class="form-control">
                <span 
                    @if( $address["postcode"] != $order['billing']["postcode"] )
                        class="text-primary"
                    @endif
                >{{ $address["postcode"] }}
                </span> - <span 
                    @if( $address["city"] != $order['billing']["city"] )
                        class="text-primary"
                    @endif
                >{{ $address["city"] }}</span>
            </div>
        </div>
        <div class="form-group">
            <span class="text-capitalize">{{ l('State') }}</span>
            <div class="input-group">
                <div class="form-control">
                    <span 
                        @if( $address["state_name"] != $order['billing']["state_name"] )
                            class="text-primary"
                        @endif
                    >{{ $address["state_name"] }}</span>
                </div>
                <span class="input-group-addon">
                    <span 
                        @if( $address["country_name"] != $order['billing']["country_name"] )
                            class="text-primary"
                        @endif
                    >{{ $address["country_name"] }}</span>
                </span>
            </div>
        </div>
