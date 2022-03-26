
@if ( Route::currentRouteName() != 'login' )
                    <a href="{{ URL::to('/') }}" style="margin-right: 15px;">
                        <button class="btn btn-default navbar-btn">
                            <i class="fa fa-user"></i> {{l('Continue', [], 'layouts')}} 
                        </button>
                    </a>
@endif
