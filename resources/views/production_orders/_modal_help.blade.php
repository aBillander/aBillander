@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="myHelpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info modal-header-help">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="_myModalLabel">Ordenes de Fabricación</h3>
      </div>
      <div class="modal-body">



<h3>Flujo de trabajo</h3>

<p>1.- Crear la Orden de Fabricación. Una Orden de Fabricación puede crearse manualmente, o ser el resultado de algún proceso de planificación (cálculo de necesidades de Productos gestionados Bajo Pedido o gestionados por punto de Pedido). Normalmente la Orden se crea en estado <strong>“Lanzada”</strong>.</p>

<p>2.- Excepcionalmente: modificar la cabecera, añadir / modificar líneas.</p>

<p>3.- Realizar la Fabricación.</p>
<p>- Sacar Materiales (Materias Primas o Semi-Elaborados) del Almacén y registrar las cantidades en aBillander.</p>
<p>- Fabricar.</p>
<p>- Entrar Producto Terminado al Almacén y registrar la cantidad en aBillander.</p>
<div class="alert alert-warning">
  <p>Los pasos anteriores pueden repetirse varias veces, por ejemplo: cuando se divide una fabricación en varias porciones, o cuando se malogra una parte de lo fabricado y son necesarios más Materiales para completar la fabricación.</p>
</div>
<p>- Devolver Materiales sobrantes al Almacén (si fuera necesario) y registrar las cantidades en aBillander.</p>

<p>4.- Finalizar la Orden. El estado de la Orden pasa a <strong>“Finalizada”</strong>. En este momento se realizan los movimientos de stock.</p>

{{--

<div class="alert alert-warning">
    <p><strong>Cómo afecta al Stock</strong></p>
    <p></p>
    <p>* Estado “Lanzada”: reserva de Stock de Materiales y de Producto Terminado (previsión de futura entrada).</p>
    <p></p>
    <p>* Estado “Finalizada”: se libera la reserva de Stock, y se aumenta / disminuye el Stock Físico de Materiales / Producto Terminado.</p>
</div>

<h3>Estado “Borrador”</h3>

<p>- Se puede modificar cualquier campo del documento.</p>

<p>- Se puede borrar el documento.</p>

<p>- No se puede enviar por email al Cliente.</p>

<p>- Si el Albarán no tiene al menos una línea, no es posible pasar al estado “Confirmado”.</p>

<p>- No es posible registrar pagos. Si fuera necesario, se hará como un Anticipo en la Cabecera del Albarán.</p>

<h3>Estado “Confirmado”</h3>

<p>- Se puede borrar el documento.</p>

<p>- No se pueden modificar algunos campos de la cabecera del Albarán.</p>

<div class="alert alert-danger">
    <p>El Documento puede volver al estado “Borrador” en cualquier momento. Por eso es posible que aparezcan “agujeros” en la numeración de los Albaranes.</p>
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

<p>- Los Albaranes cerrados son visibles por el Cliente en el Centro de Clientes.</p>

<div class="alert alert-danger">
    <p>El Documento puede abrirse de nuevo, es decir, volver al estado “Confirmado”. En este caso:</p>
    <p></p>
    <p>* Los movimientos de stock se revierten añadiendo movimientos en sentido contrario.</p>
</div>

<h3>Estados de Envío</h3>

<p>- <strong>Pendiente</strong>. El Albarán se ha creado a partir de un Pedido o directamente. Es estado es automáticamente Pendiente. Aún no se está haciendo nada con él (está a la espera del fulfillment: picking y packing).</p>

<p>- <strong>Preparación</strong>. Se pasa a este estado cuando se empieza a preparar el Albarán, es decir, se está realizando el picking (proceso de recolección de los artículos desde las distintas ubicaciones en el almacén) y el packing (empaquetado de los pedidos para su entrega).</p>

<p>- <strong>Reparto</strong>. Cuando se entregan los paquetes al Transportista.</p>

<p>- <strong>Excepción</strong>. El Albarán pasa a este estado si la entrega falla (entrega rehusada, pérdida de paquetes, etc.).</p>

<p>- <strong>Entregado</strong>. La mercancía se entregó y el Albarán está firmado por el Cliente y el Transportista.</p>


--}}




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




