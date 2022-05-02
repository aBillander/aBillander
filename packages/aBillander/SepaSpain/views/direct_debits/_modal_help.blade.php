@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="myHelpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info modal-header-help">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="_myModalLabel">Remesas SEPA</h3>
      </div>
      <div class="modal-body">

<!-- Pendiente, En trámite, Cargado en cuenta -->

<h3>Flujo de trabajo</h3>

<p>1.- Crear una Remesa. La Remesa se crea en estado <strong>“Pendiente”</strong>.</p>

<p>2.- Gestionar la Remesa. Podrá añadir más Recibos a la Remesa. También podrá quitar Recibos de la Remesa, uno a uno, o seleccionando varios a la vez.</p>

<p>3.- Generar Fichero para el Banco. El Estado de la Remesa pasa de <strong>“Pendiente”</strong> a <strong>“En trámite”</strong>, y ya no se podrá añadir o quitar Recibos a la Remesa.</p>

<div class="alert alert-danger" style="margin-left: 10%;width: 80%;">
    <p>Esta limitación es una característica de seguridad del Sistema, para evitar discrepancias entre la Remesa y el fichero enviado al Banco.</p>
    <p>No obstante, si fuera necesario añadir o quitar Recibos, es posible devolver la Remesa al estado <strong>“Pendiente”</strong>, mediante un botón situado a la derecha del indicador de estado.</p>
</div>

<p>4.- Gestionar el Cobro de la Remesa. Podrá marcar los Recibos como cobrados (uno a uno, o seleccionando varios a la vez), o como Devueltos. Cuando todos los Recibos de la Remesa se han cobrado (o devuelto), el Estado de la Remesa pasa de <strong>“En trámite”</strong> a <strong>“Cargado en cuenta”</strong>.</p>

<div class="alert alert-warning">
    <p><strong>Recibo Devuelto</strong></p>
    <p></p>
    <p>Cuando se marca un Recibo como <strong>“Devuelto”</strong>, se genera un Recibo nuevo por igual importe, en estado <strong>“Pendiente”</strong>. El Recibo devuelto se queda en la Remesa (a efectos de trazabilidad), y el Recibo nuevo se puede añadir a otra Remesa, o cambiar el <i>“Tipo de Pago”</i> según convenga.</p>
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




