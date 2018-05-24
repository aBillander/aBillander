
{!! Form::model($category, array('method' => 'PATCH', 'route' => array('categories.subcategories.update', $parentId, $category->id))) !!}
<input type="hidden" value="main_data" name="tab_name" id="tab_name">

          <div class="panel panel-primary" id="panel_main_data">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }}</h3>
               </div>
                

                    @include('categories._form')


		  </div>
{!! Form::close() !!}
