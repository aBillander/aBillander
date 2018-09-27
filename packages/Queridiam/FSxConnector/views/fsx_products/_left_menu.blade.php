      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">

            <a id="tab_index_1" href="{{ route('fsxproducts.index').'?tab_index=1' }}" class="list-group-item @if ($tab_index==1) active @endif">
               <i class="fa fa-database"></i>
               &nbsp; {{ l('Cargar FactuSOLWeb') }}
            </a>

            <a id="tab_index_7" href="{{ route('fsxproducts.index').'?tab_index=7' }}" class="list-group-item @if ($tab_index==7) active @endif">
               <i class="fa fa-refresh"></i></span>
               &nbsp; {{ l('Importar') }}
            </a>
         </div>

      </div>