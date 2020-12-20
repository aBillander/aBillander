@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="myHelpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info modal-header-help">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="_myModalLabel">Facturas de Clientes</h3>
      </div>
      <div class="modal-body">



<h3>Flujo de trabajo</h3>

<p>1.- Crear la Factura. El Documento se crea en estado <strong>“Borrador”</strong>.</p>

<p>2.- Modificar la cabecera, añadir / modificar / borrar líneas.</p>

<p>3.- Confirmar la Factura. El estado del Documento pasa a <strong>“Confirmado”</strong>. En este momento se asigna un número al Documento, según la Serie asignada, y se hace la reserva de Stock. Aún es posible modificar la cabecera y las líneas.</p>

<p>4.- Cerrar la Factura. El estado del Documento pasa a <strong>“Cerrado”</strong>. En este momento se realizan los movimientos de stock, se calculan los vencimientos, según la forma de pago de la factura, y se actualiza el riesgo del Cliente.</p>

<p>5.- Pagar completamente la Factura. El estado del Documento pasa a <strong>“Archivado”</strong>.</p>

<p>6.- Impago de la Factura. El estado del Documento pasa a <strong>“Pago Dudoso”</strong>.</p>

<div class="alert alert-warning">
    <p><strong>Cómo afecta al Stock</strong></p>
    <p></p>
    <p>* Estado “Confirmado”: reserva de Stock.</p>
    <p></p>
    <p>* Estado “Cerrado”: se libera la reserva de Stock, y se disminuye el Stock Físico.</p>
    <p></p>
    <p></p>

<div class="alert alert-danger">
    <p>Si la Factura se creó a partir de uno o más Albaranes, entonces no tendrá incidencia sobre el Stock Físico, ni tampoco sobre el Stock Reservado, ya que estas acciones ya las realizaron los Albaranes.</p>
</div>

</div>

<div class="alert alert-warning">
  <p>Sólo las Facturas cerradas, así como sus Vencimientos correspondientes, son visibles por el Cliente en el Centro de Clientes.</p>
</div>

<h3>Estado “Borrador”</h3>

<p>- Se puede modificar cualquier campo del documento.</p>

<p>- Se puede borrar el documento.</p>

<p>- No se puede enviar por email al Cliente.</p>

<p>- Si la Factura no tiene al menos una línea, no es posible pasar al estado “Confirmado”.</p>

<p>- No es posible registrar pagos. Si fuera necesario, se hará como un Anticipo en la Cabecera de la Factura.</p>

<h3>Estado “Confirmado”</h3>

<p>- No se puede borrar el documento.</p>

<p>- No se pueden modificar algunos campos de la cabecera de la Factura.</p>

<div class="alert alert-danger">
    <p>El Documento puede volver al estado “Borrador”, sólo en el caso de que sea el último de la Serie que tiene asignada. De lo contrario, se crearían “agujeros” en la numeración de las Facturas.</p>
</div>

<h3>Estado “Cerrado”</h3>

<div class="alert alert-info">
    <p><strong>El Documento se cierra cuando:</strong></p>
    <p></p>
    <p>* Se imprime.</p>
    <p></p>
    <p>* Se envía al Cliente por correo electrónico.</p>
    <p></p>
    <p>* Se cierra pulsando un botón habilitado para ello.</p>
</div>

<p>- No se puede modificar la cabecera y tampoco las líneas del Documento.</p>

<p>- Las Facturas cerradas y sus Vencimientos correspondientes son visibles por el Cliente en el Centro de Clientes.</p>

<div class="alert alert-danger">
    <p>El Documento puede abrirse de nuevo, es decir, volver al estado “Confirmado”. En este caso:</p>
    <p></p>
    <p>* Los movimientos de stock se revierten añadiendo movimientos en sentido contrario.</p>
    <p></p>
    <p>* Los vencimientos se borran.</p>
    <p></p>
    <p>* El riesgo del Cliente se recalcula.</p>
</div>

<h3>Estados de Pago</h3>

<p>- <strong>Pendiente</strong>. No se ha realizado ningún pago aún.</p>

<p>- <strong>Pagado parcialmente</strong>. La Factura ya no puede modificarse. Si es necesario modificar la forma de pago, se hará modificando directamente los vencimientos existentes o creando otros nuevos.</p>

<p>- <strong>Pagado</strong>. El importe total de la factura se ha pagado, y el estado del Documento pasa a “Archivado”.</p>

<div class="alert alert-info">
    <p>Para calcular Vencimientos y registrar Pagos, es necesario que la Factura esté en estado “Cerrado”. Si fuera necesario registrar un Pago (un anticipo) en un estado anterior (“Borrador” o "Confirmado"), se hará como un Anticipo en la Cabecera de la Factura.</p>
</div>





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




