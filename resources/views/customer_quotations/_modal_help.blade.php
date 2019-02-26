@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="myHelpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info modal-header-help">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">Presupuestos de Clientes **BORRADOR**</h3>
      </div>
      <div class="modal-body">



<h3>Flujo de trabajo</h3>

<p>1.- Crear el Presupuesto. El Documento se crea en estado <strong>“Borrador”</strong>.</p>

<p>2.- Modificar la cabecera, añadir / modificar / borrar líneas.</p>

<p>3.- Confirmar el Presupuesto. El estado del Documento pasa a <strong>“Confirmado”</strong>. En este momento se asigna un número al Documento, según la Serie asignada. Aún es posible modificar la cabecera y las líneas.</p>

<p>4.- Cerrar el Presupuesto. El estado del Documento pasa a <strong>“Cerrado”</strong>.</p>

<div class="alert alert-warning">
  <p>Todos los Presupuestos (en cualquier Estado) son visibles por el Cliente en el Centro de Clientes.</p>
</div>

<h3>Estado “Borrador”</h3>

<p>- Se puede modificar cualquier campo del documento.</p>

<p>- Se puede borrar el documento.</p>

<p>- Si el Presupuesto no tiene al menos una línea, no es posible pasar al estado “Confirmado”.

<p>- No es posible registrar pagos. Si fuera necesario, se hará como un Anticipo en la Cabecera del Presupuesto.</p>

<h3>Estado “Confirmado”</h3>

<p>- Se puede borrar el documento.</p>

<p>- Se puede crea un Albarán.</p>

<div class="alert alert-danger">
    <p>El Documento puede borrar en cualquier momento. Por eso es posible que aparezcan “agujeros” en la numeración de los Presupuestos.</p>
</div>

<h3>Estado “Cerrado”</h3>

<div class="alert alert-info">
    <p><strong>El Documento se cierra cuando:</strong></p>
    <p></p>
    <p>* Se crea un Albarán.</p>
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




