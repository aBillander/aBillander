@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="myHelpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info modal-header-help">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">Pedidos de Clientes</h3>
      </div>
      <div class="modal-body">



<h3>Flujo de trabajo</h3>

<p>1.- Crear el Pedido. El Documento se crea en estado <strong>“Borrador”</strong>.</p>

<p>2.- Modificar la cabecera, añadir / modificar / borrar líneas.</p>

<p>3.- Confirmar el Pedido. El estado del Documento pasa a <strong>“Confirmado”</strong>. En este momento se asigna un número al Documento, según la Serie asignada, y se hace la reserva de Stock. Aún es posible modificar la cabecera y las líneas.</p>

<p>4.- Cerrar el Pedido. El estado del Documento pasa a <strong>“Cerrado”</strong>.</p>

<div class="alert alert-warning">
    <p><strong>Cómo afecta al Stock</strong></p>
    <p></p>
  <p>El Pedido nunca tiene efecto sobre el Stock Físico. Sólo mientras está en Estado “Confirmado” afecta al Stock Reservado.</p>
</div>

<div class="alert alert-warning">
  <p>Todos los Pedidos (en cualquier Estado) son visibles por el Cliente en el Centro de Clientes.</p>
</div>

<h3>Estado “Borrador”</h3>

<p>- Se puede modificar cualquier campo del documento.</p>

<p>- Se puede borrar el documento.</p>

<p>- Si el Pedido no tiene al menos una línea, no es posible pasar al estado “Confirmado”.</p>

<p>- No es posible registrar pagos. Si fuera necesario, se hará como un Anticipo en la Cabecera del Pedido.</p>

<h3>Estado “Confirmado”</h3>

<p>- Se puede borrar el documento.</p>

<p>- Se puede crear un Albarán.</p>

<div class="alert alert-danger">
    <p>El Documento se puede borrar en cualquier momento. Por eso es posible que aparezcan “agujeros” en la numeración de los Pedidos.</p>
</div>

<h3>Estado “Cerrado”</h3>

<div class="alert alert-info">
    <p><strong>El Documento se cierra cuando:</strong></p>
    <p></p>
    <p>* Se crea un Albarán. En este momento se libera la reserva de Productos.</p>
</div>

<p>- No se puede modificar la cabecera y tampoco las líneas del Documento.</p>

<!-- div class="alert alert-danger">
    <p>El Documento puede abrirse de nuevo, es decir, volver al estado “Confirmado”. En este caso:</p>
    <p></p>
    <p>* Los movimientos de stock se revierten añadiendo movimientos en sentido contrario.</p>
</div -->




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




