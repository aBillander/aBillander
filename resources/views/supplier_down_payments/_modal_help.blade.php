
@section('modals')    @parent

<div class="modal fade" id="supplierdownpaymentsHelp" tabindex="-1" role="dialog" aria-labelledby="myLaraBillander" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myLaraBillander">Anticipos a Proveedores</h3>
            </div>
            <div class="modal-body">


<h3>Flujo de trabajo</h3>

<p>1.- Crear el Anticipo.</p>

<p>El Anticipo a Proveedor se crea desde un Pedido a Proveedor, en la pestaña <i>Anticipos</i>. El Pedido puede estar en cualquier Estado, aunque se recomienda que esté como mínimo "Confirmado". Un Pedido puede tener varios Anticipos. El Anticipo se crea en Estado "Pendiente".</p>

<p>El Anticipo genera un Recibo en Estado "Pagado", que aparece también en la consulta "Recibos de Proveedores". No es posible deshacer el pago de un Recibo correspondiente a un Anticipo; en caso de error, deberá borrar el Anticipo, y se borrará también el Recibo (Pago) asociado.</p>

<p>2.- Crear Albarán y Factura de Proveedor</p>

<p>El Pedido a Proveedor pasará a Albarán y luego a Factura. En la Factura de Proveedor, en la pestaña <i>Pagos</i> se podrán visualizar los Anticipos que corresponden a esa Factura; son los que provienen de los Pedidos que corresponden a esa Factura.</p>

<p>3.- Cerrar la Factura de Proveedor</p>

<p>Cuando la Factura se cierra, el cálculo de Vencimientos se hace así:</p>

<p>a) Se aplican los Pagos correspondientes a los Anticipos. El Estado de los Anticipos pasa a "Aplicado", y ya no es posible modificarlos o borrarlos.</p>

<p>b) Se toma la diferencia entre el total de la Factura y el total de los Anticipos. Con este importe se calculan los Vencimientos restantes según la Forma de Pago dada en la Factura.</p>

<div class="alert alert-warning">
  <p>Cuando se aplica un Anticipo a una Factura, el Pago correspondiente seguirá vinculado al Anticipo, pero también a la Factura a la que se aplicó el Anticipo.</p>
</div>

<p>Si la Factura se vuelve a abrir:</p>

<p>a) Los Anticipos pasan a Estado "Pendiente".</p>

<p>b) Los Recibos pendientes de pago se borran.</p>

<div class="alert alert-danger">
  <p>Una Factura en estado "Cerrado" no se puede abrir si se han registrado Pagos además de los Anticipos.</p>
</div>


<h3>Estados del Anticipo</h3>

<p>- <strong>Pendiente</strong>. El Anticipo se ha desembolsado, pero todavía no se ha aplicado a la Factura.</p>

<p>- <strong>Aplicado</strong>. El Anticipo se ha aplicado como un Pago a la Factura.</p>

{{--
<p>2.- Vincular Recibos.</p>

<p>Una vez creado el Cheque, es posible indicar los Recibos que se pagan con el Cheque. Para ello, ir a la pestaña “Detalle” y pulsar el botón “Nuevo”.  Aparecerá una ventana emergente donde se muestran los Recibos pendientes del Cliente que ha emitido el Cheque. En esta ventana se seleccionarán los Recibos y el importe que corresponde de cada uno.</p>

<p>Cuando se pulsa “Aceptar”, los Recibos seleccionados no cambiarán de Estado (seguirán en Estado “Pendiente”), pero el “Tipo de Pago” se cambiará al definido en Configuración > Valores por defecto > Tipo de Pago para Cheques. Si para algún Recibo seleccionó un importe inferior al total del Recibo, se creará un Recibo en Estado “Pendiente” por la diferencia.</p>

<p>Si algún Recibo se hubiera asignado incorrectamente al Cheque, se puede desvincular del Cheque; el Recibo no se altera.</p>

<p>3.- Ingresar el Cheque.</p>

<p>Se debe indicar una <strong>Fecha de Pago</strong>, y cambia el "Estado" del Cheque y de los Recibos a "Pagado". Esto se puede realizar de dos maneras:</p>

<p>a) Pulsando el botón "Ingresar Cheque".</p>

<p>b) En Cheque > Datos Generales, indique una <strong>Fecha de Pago</strong> y pulse "Guardar".</p>

<p>4.- Devolver el Cheque.</p>

<p>Si el Cheque resulta devuelto, debe indicarlo en aBillander pulsando el botón "Devolver Cheque". La <strong>Fecha de Pago</strong> se vacía, y cambia el "Estado" del Cheque y de los Recibos a "Pendiente". Además, los Recibos se desvinculan del Cheque.</p>

<h3>Estados del Cheque</h3>

<p>- <strong>Pendiente</strong>. El Cheque está aún en poder del Cliente</p>

<p>- <strong>Depositado</strong>. El Cheque está en poder de la Empresa.</p>

<p>- <strong>Pagado</strong>.</p>

<p>- <strong>Anulado</strong>.</p>

<p>- <strong>Devuelto</strong>.</p>


<div class="alert alert-warning">
  <p>Los Estados "Pendiente", "Depositado" y "Anulado" son para control interno, no implican acción alguna sobre el Cheque o sobre los Recibos asociados.</p>
</div>


<h2>Trazabilidad</h2>

<p>1.- Desde Cheque > Detalles se puede ir al Recibo.</p>

<p>2.- Desde las consultas de Recibos de Cliente (Recibos, Recibos por Cliente, Pagos de una Factura) se puede ir al Cheque.</p>
--}}
{{--
<h2>¿Qué es una <i>Plantilla para Pedidos de Venta</i>?</h2>
<p>Una <strong><i>Plantilla para Pedidos de Venta</i></strong> es un Modelo para crear rápidamente Pedidos de Venta. Las Plantillas para Pedidos se utilizan convenientemente cuando un Cliente hace pedidos regularmente con el mismo contenido o similar.</p>

<p>Tenga en cuenta que:</p>

<p>1.- Un Pedido creado a partir de una Plantilla puede ser modificado sin restricciones.</p>

<p>2.- Cuando crea un Pedido a partir de una Plantilla, se añadirán los Costes de Envío correspondientes a la Dirección de Entrega de la Plantilla.</p>
--}}



{{--
<p>1.- El Cliente esté dado de alta en aBillander.</p>

<p>2.- El Administrador debe permitir el acceso al Centro de Clientes.</p>

<p>Para conseguir esto, se puede hacer de dos maneras, que se explican a continuación.</p>



<h2>Acceso al Centro de Clientes :: Método 1</h2>
<p>1.- El Administrador crea el Cliente en aBillander.</p>
<p>2.- El Administrador permite el acceso al Centro de Clientes en la Ficha del Cliente, pestaña <em>Acceso ABCC</em>. En ese momento se manda automáticamente un email al Cliente comunicándole los datos de acceso.</p>
<p>Nota 1: En <em>Configuración -> Centro de Clientes</em> se define un valor por defecto para la Contraseña de acceso al Centro de Clientes. Sin embargo, al permitir el acceso puede cambiarse este valor.</p>
<p>Nota 2: También es posible indicar al Cliente que vaya al enlace <a href="javascript:void(0);">site/abcc/password/reset/</a> para crear una Contraseña nueva.</p>

<h2>Acceso al Centro de Clientes :: Método 2</h2>
<p>Este método asume que el Cliente NO existe en aBillander.</p>
<p>1.- El Administrador invita a un Cliente a registrarse en el Centro de Clientes. Para ello hace clic en el botón <button type="button" class="btn btn-xs btn-navy"><i class="fa fa-paper-plane"></i> {{l('Invite')}}</button> y completa los datos.</p>
<p>2.- El Cliente recibe un email con la dirección donde debe registrar sus datos.</p>
<p>3.- Cuando el Cliente ha registrado sus datos, automáticamente:</p>
<p> &nbsp;  &nbsp;  &nbsp;  &nbsp; a) El Cliente recibe un email de confirmación.</p>
<p> &nbsp;  &nbsp;  &nbsp;  &nbsp; b) El Administrador recibe un email informándole que un Cliente se ha registrado. Este email incluye un enlace a la Ficha del nuevo Cliente para que el Administrador pueda validar el acceso.</p>
<p> &nbsp;  &nbsp;  &nbsp;  &nbsp; c) Se crea una Tarea para recordar al Administrador que ha de validar el acceso. Que la tarea está pendiente se indica en el Menú de Usuario, en la esquina superior derecha.</p>
<p>4.- El Administrador permite el acceso al Centro de Clientes en la Ficha del Cliente, pestaña <em>Acceso ABCC</em>. En ese momento se manda automáticamente un email al Cliente comunicándole los datos de acceso.</p>




<h2>Example body text</h2>
<p>Nullam quis risus eget <a href="#">urna mollis ornare</a> vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula.</p>
<p><small>This line of text is meant to be treated as fine print.</small></p>
<p>The following snippet of text is <strong>rendered as bold text</strong>.</p>
<p>The following snippet of text is <em>rendered as italicized text</em>.</p>
<p>An abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.</p>
--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{l('Continue', [], 'layouts')}}</button>

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

