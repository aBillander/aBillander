
            <div class="panel panel-primary" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }}</h3>
               </div>

              <div class="panel-body">

                @include('errors.list')

                {!! Form::model($lead, array('method' => 'PATCH', 'route' => array('leads.update', $lead->id))) !!}

                  @include('leads._form')

                {!! Form::close() !!}
              </div>

            </div>
