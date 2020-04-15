@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="myHelpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info modal-header-help">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="_myModalLabel">Hojas de Producción</h3>
      </div>
      <div class="modal-body">



<h3>Proceso de Planificación</h3>

<br />

<p>Esto es lo que sucede al pulsar el botón <a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="return false;"><i class="fa fa-cog"></i> {{ l('Update Sheet') }}</a> :</p>

<br />

<h4>Paso 1 :: Calcular las necesidades de Productos Terminados</h4>
<p>a) Requerimientos brutos: agrupar las Líneas de Pedidos de Clientes (Productos con <i>procurement_type</i> = <strong>"manufacture"</strong>).</p>
<p>b) Si hay algún Producto con <i>stock_control</i> = <strong>"Sí"</strong>, se descuenta el stock disponible (físico) de la cantidad a fabricar.</p>
<p>c) Se ajusta la cantidad según el tamaño de lote (si es necesario).</p>
<p>d) Calcular las necesidades brutas de Semi-elaborados.</p>

<br />

<h4>Paso 2 :: Calcular las necesidades de Productos Semi-elaborados</h4>
<p>a) Agrupar los requerimientos de Semi-elaborados (Productos con <i>procurement_type</i> = <strong>"assembly"</strong>).</p>
<p>b) Requerimientos netos: descontar de la cantidad a fabricar el Stock de los Productos con <i>mrp_type</i> = <strong>"manual"</strong> o <i>mrp_type</i> = <strong>"reorder"</strong>. Estos Productos se gestionan fuera de las Hojas de Producción normales.</p>

<div class="alert alert-warning">
    <p><strong>Productos Semi-elaborados con <i>mrp_type</i> = <strong>"manual"</strong> o <i>mrp_type</i> = <strong>"reorder"</strong></strong></p>
    <p></p>
  <p>Si uno de estos Productos tiene una Lista de Materiales que incluye otro Producto Semi-elaborado, éste último debe tener <i>mrp_type</i> = <strong>"onorder"</strong>, ya que la demanda de este Producto es "dependiente" de la demanda del primero.</p>
</div>

<h4>Paso 3 :: Ajustar al Tamaño de Lote de Fabricación</h4>

<br />

<h4>Paso 4 :: Crear las Ordenes de Fabricación</h4>

<br />




      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-grey" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
      </div>
    </div>
  </div>
</div>

@endsection



@section('styles')    @parent

<style>

.modal-content {
  overflow:hidden;
}

/*
See: https://coreui.io/docs/components/buttons/ :: Brand buttons
*/
.btn-behance {
    color: #fff;
    background-color: #1769ff;
    border-color: #1769ff;
}

</style>

@endsection




