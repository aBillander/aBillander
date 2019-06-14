@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="myHelpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info modal-header-help">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">Listas de Materiales (BOM)</h3>
      </div>
      <div class="modal-body">



<h3>Crear una Lista de Materiales</h3>

<p>1.- Rellenar datos de cabecera:</p>

<p>- <strong>Alias</strong>. Nombre corto de la Lista de Materiales.</p>

<p>- <strong>Nombre</strong>. Nombre corto de la Lista de Materiales.</p>

<p>- <strong>Cantidad</strong>. Los Ingredientes se especificarán para esta cantidad de Elaborado.</p>

<p>- <strong>Unidad de Medida</strong>. Unidad de Medida del Elaborado resultante de combinar los Ingredientes de la lista.</p>

<p>2.- Asignar los Materiales o Ingredientes a la Lista. La Cantidad y la Unidad de Medida de cada uno debe ser consistente con los valores definidos en el punto anterior.</p>

<br />

<div class="alert alert-info">
    <p><strong>Ejemplo:</strong> Definir la Lista de Materiales para una masa que se elabora a partir de un 60% (en peso) de harina y el resto (40% en peso) de agua.</p>
    <p></p>
    <p>Las Listas de Materiales a continuación son equivalentes.</p>
</div>

<br />

<div class="alert alert-warning">

<p>- <strong>Alias</strong>. BOM-FLOUR60-1.</p>

<p>- <strong>Nombre</strong>. Masa de Harina y Agua 60/40</p>

<p>- <strong>Cantidad</strong>. 1</p>

<p>- <strong>Unidad de Medida</strong>. gramo</p>

<p>- <strong>Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 0,6 gramo</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 0,4 gramo</p>
</div>

<br />

<div class="alert alert-warning">

<p>- <strong>Alias</strong>. BOM-FLOUR60-2.</p>

<p>- <strong>Nombre</strong>. Masa de Harina y Agua 60/40</p>

<p>- <strong>Cantidad</strong>. 1000</p>

<p>- <strong>Unidad de Medida</strong>. gramo</p>

<p>- <strong>Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 600 gramo</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 400 gramo</p>
</div>

<br />

<div class="alert alert-warning">

<p>- <strong>Alias</strong>. BOM-FLOUR60-3.</p>

<p>- <strong>Nombre</strong>. Masa de Harina y Agua 60/40</p>

<p>- <strong>Cantidad</strong>. 1</p>

<p>- <strong>Unidad de Medida</strong>. kilo</p>

<p>- <strong>Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 600 gramo</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 400 gramo</p>
</div>

<br />

<div class="alert alert-warning">

<p>- <strong>Alias</strong>. BOM-FLOUR60-4.</p>

<p>- <strong>Nombre</strong>. Masa de Harina y Agua 60/40</p>

<p>- <strong>Cantidad</strong>. 25</p>

<p>- <strong>Unidad de Medida</strong>. gramo</p>

<p>- <strong>Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 15 gramo</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 10 gramo</p>
</div>

<br />

<div class="alert alert-warning">

<p>- <strong>Alias</strong>. BOM-FLOUR60-5.</p>

<p>- <strong>Nombre</strong>. Masa de Harina y Agua 60/40</p>

<p>- <strong>Cantidad</strong>. 0,25</p>

<p>- <strong>Unidad de Medida</strong>. kilo</p>

<p>- <strong>Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 150 gramo</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 100 gramo</p>
</div>

<br />





<h3>Asignar una Lista de Materiales a un Producto</h3>

<p>1.- Rellenar datos de cabecera:</p>

<p>- <strong>Cantidad</strong>. La Cantidad depende de la Unidad de Medida del Producto y de la Unidad de Medida de la Lista de Materiales.</p>

<p>- <strong>Nombre de la Lista de Materiales </strong>. La Lista que se quiere asignar al Producto.</p>

<p>2.- Aceptar la selección.</p>

<br />

<div class="alert alert-info">
    <p><strong>Ejemplo:</strong> Asignar la Lista de Materiales a un Pan que se vende por unidades. Este Pan requiere 375 gramos de Masa de Harina 60/40.</p>
    <p></p>
    <p>Las asignaciones a continuación son equivalentes.</p>
</div>

<br />

<div class="alert alert-danger">

<p>- <strong>Cantidad</strong>. 375. </p>

<p>- <strong>Nombre de la Lista de Materiales</strong>. BOM-FLOUR60-1</p>

<p>- <strong>Cálculo de Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 375 * 0,6 / 1  = 225 gramo</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 375 * 0,4 / 1 = 150 gramo</p>
</div>

<br />

<div class="alert alert-danger">

<p>- <strong>Cantidad</strong>. 375 (ya que la Unidad de la Lista es "gramo", es decir, al mezclar los componentes de la Lista se obtiene cierta cantidad de gramos)</p>

<p>- <strong>Nombre de la Lista de Materiales</strong>. BOM-FLOUR60-2</p>

<p>- <strong>Cálculo de Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 375 * 600 / 1000  = 225 gramo (ya que la cantidad de Harina de la Lista se ha dado para 1000 gramos de Elaborado)</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 375 * 400 / 1000 = 150 gramo</p>
</div>

<br />

<div class="alert alert-danger">

<p>- <strong>Cantidad</strong>. 0,375 (como la Unidad de la Lista es "kilo", cada unidad de pan requiere 0,375 kilos)</p>

<p>- <strong>Nombre de la Lista de Materiales</strong>. BOM-FLOUR60-3</p>

<p>- <strong>Cálculo de Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 0,375 * 600 / 1 = 225 gramo (ya que la cantidad de Harina de la Lista se ha dado para 1 kilo de Elaborado)</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 0,375 * 400 / 1 = 150 gramo</p>
</div>

<br />

<div class="alert alert-danger">

<p>- <strong>Cantidad</strong>. 375</p>

<p>- <strong>Nombre de la Lista de Materiales</strong>. BOM-FLOUR60-4</p>

<p>- <strong>Cálculo de Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 375 * 15 / 25  = 225 gramo</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 375 * 10 / 25 = 150 gramo</p>
</div>

<br />

<div class="alert alert-danger">

<p>- <strong>Cantidad</strong>. 0,375</p>

<p>- <strong>Nombre de la Lista de Materiales</strong>. BOM-FLOUR60-5</p>

<p>- <strong>Cálculo de Ingredientes</strong>:</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Harina</strong>. 0,375 * 150 / 0,25 = 225 gramo</p>

<p> &nbsp; &nbsp; &nbsp; <strong>Agua</strong>. 0,375 * 100 / 0,25 = 150 gramo</p>
</div>

<br />










<!-- p>3.- Confirmar el Pedido. El estado del Documento pasa a <strong>“Confirmado”</strong>. En este momento se asigna un número al Documento, según la Serie asignada. Aún es posible modificar la cabecera y las líneas.</p>

<p>4.- Cerrar el Pedido. El estado del Documento pasa a <strong>“Cerrado”</strong>.</p>

<div class="alert alert-warning">
  <p>Todos los Pedidos (en cualquier Estado) son visibles por el Cliente en el Centro de Clientes.</p>
</div>

<h3>Estado “Borrador”</h3>

<p>- Se puede modificar cualquier campo del documento.</p>

<p>- Se puede borrar el documento.</p>

<p>- Si el Pedido no tiene al menos una línea, no es posible pasar al estado “Confirmado”.

<p>- No es posible registrar pagos. Si fuera necesario, se hará como un Anticipo en la Cabecera del Pedido.</p>

<h3>Estado “Confirmado”</h3>

<p>- Se puede borrar el documento.</p>

<p>- Se puede crea un Albarán.</p>

<div class="alert alert-danger">
    <p>El Documento puede borrar en cualquier momento. Por eso es posible que aparezcan “agujeros” en la numeración de los Pedidos.</p>
</div>

<h3>Estado “Cerrado”</h3>

<div class="alert alert-info">
    <p><strong>El Documento se cierra cuando:</strong></p>
    <p></p>
    <p>* Se crea un Albarán.</p>
</div>

<p>- No se puede modificar la cabecera y tampoco las líneas del Documento.</p -->

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




