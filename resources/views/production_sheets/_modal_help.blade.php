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



<h3>Definiciones</h3>

<br />

<h4>Configuración</h4>

<p><strong>Configuración</strong> &gt; <strong>Mi Empresa</strong> &gt; <strong>MRP_WITH_STOCK</strong></p>

<p>- <i>Sí</i>. Calcula la Hoja de Producción teniendo en cuenta el Stock Físico.</p>

<p>- <i>No</i>. Calcula las necesidades de Productos Terminados y Semi-Elaborados SIN tener en cuenta el Stock Físico. Normalmente este valor se usa en pruebas del Sistema.</p>

<br />

<p><strong>Configuración</strong> &gt; <strong>Mi Empresa</strong> &gt; <strong>MRP_ONORDER_WITHOUT_REORDER</strong></p>

<p>- <i>Sí</i>. Cuando la Hoja de Producción es de Tipo "Completar Pedidos de Clientes", se calcula SIN tener en cuenta los Productos con <i>Planificación</i> = <i>"Punto de Pedido"</i>. Normalmente este valor se usa en pruebas del Sistema.</p>

<p>- <i>No</i>. La Hoja de Producción se calcula como se explica más abajo.</p>

<br />

<h4>Productos</h4>

<p><strong>Aprovisionamiento</strong></p>

<p>- <i>Fabricación</i>. Tiene una Lista de Materiales (BOM) asociada, pero NO aparece como Material requerido en otra BOM. Es decir, los Productos de Fabricación están al nivel más alto de una BOM. El Proceso de Planificación genera Ordenes de Fabricación para estos Productos.</p>

<p>- <i>Semi-Elaborado</i>. Tiene una Lista de Materiales (BOM) asociada, y además aparece como Material requerido en otra BOM. Es decir, estos Productos son necesarios para fabricar otro Producto de nivel superior (Semi-Elaborado o de Fabricación). El Proceso de Planificación genera Ordenes de Fabricación para estos Productos.</p>

<div class="alert alert-warning">
    <p><strong>Productos Terminados</strong> (Aprovisionamiento: Fabricación) con <i>Planificación</i> = <strong>"Manual"</strong> o <i>Planificación</i> = <strong>"Punto de Pedido"</strong>, o <strong>Productos Semi-elaborados</strong> (Aprovisionamiento: Semi-Elaborado)</p>
    <p></p>
  <p>Si uno de estos Productos tiene una Lista de Materiales que incluye otro Producto Semi-elaborado, éste último debe tener <i>Planificación</i> = <strong>"Bajo Pedido"</strong>, ya que la demanda de este Producto es "dependiente" de la demanda del primero.</p>
</div>

<p>- <i>Compras</i>. Estos Productos son necesariaos para fabricar otro Producto de nivel superior, Semi-Elaborado o de Fabricación, y no tienen una BOM asociada. El Proceso de Planificación calcula las cantidades necesarias para las Ordenes de fabricación creadas.</p>

<br />

<h4>Hojas de Producción</h4>

<p><strong>Tipo</strong></p>

<p> El Tipo de una Hoja de Producción se asigna cuando se crea la Hoja, pero puede cambiarse en cualquier momento. Dependiendo del valor de este campo, el cálculo es diferente cuando se pulsa el botón "Actualizar Hoja de Producción".</p>

<p>- <i>Completar Pedidos de Clientes</i>. Los Pedidos de Clientes aparecen en la sección "Demanda Independiente". La sección "Requerimientos de Producción" no aparece.</p>

<p>- <i>Reaprovisionar Almacén</i>. En la sección "Demanda Independiente" habrá Pedidos del "Cliente Almacén" (Cliente ficticio). La sección "Requerimientos de Producción" contendrá Productos (Aprovisionamiento: Semi-Elaborado), que se incorporarán a las Ordenes de Fabricación necesarias para los Productos que aparecen en la Demanda Independiente.</p>

<br />

<p><strong>Demanda</strong></p>

<p> Es la base para calcular las necesidades de Fabricación y de Materias Primas.</p>

<p>- <i>Demanda Independiente</i> (Pedidos de Clientes). La Demanda Independiente está formada por Productos Terminados (Aprovisionamiento: Fabricación), y se introduce mediante Pedidos de Clientes.</p>

<p>- <i>Demanda Dependiente</i> (Requerimientos de Producción). Estos Productos se incorporarán físicamente a la Demanda Independiente. Pueden considerarse como Semi-Elaborados que se fabrican antes de tener las Ordenes de Fabricación de los Productos terminados que forman la Demanda Independiente.</p>

<br />

<h3>Proceso de Planificación</h3>

<br />

<p>Esto es lo que sucede al pulsar el botón <a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="return false;"><i class="fa fa-cog"></i> {{ l('Update Sheet') }}</a> El cálculo depende del Tipo de la Hoja de Producción.</p>

<br />

<h3>Hoja Tipo: Completar Pedidos de Clientes</h3>

<br />

<h4>Paso 1 :: Calcular las necesidades de Productos Terminados</h4>
<p>a) Requerimientos brutos: agrupar las Líneas de Pedidos de Clientes (Productos con Aprovisionamiento: Fabricación).</p>
<p>b) Requerimientos netos: Productos con <i>Planificación</i> = <strong>"Bajo Pedido"</strong>, NO se descuenta el stock disponible (físico) de la cantidad a fabricar (si lo hubiera).</p>
<p>c) Requerimientos netos: Productos con <i>Planificación</i> = <strong>"Punto de Pedido"</strong>, se descuenta el stock disponible (físico) de la cantidad a fabricar. Si no hay suficiente, se generan las Ordenes de Fabricación necesarias.</p>

<br />

<h4>Paso 2 :: Calcular las necesidades de Productos Semi-elaborados</h4>
<p>a) Agrupar los requerimientos de Semi-elaborados (Productos con Aprovisionamiento: Semi-Elaborado).</p>
<p>b) Requerimientos netos: se descuenta el stock disponible (físico) de la cantidad a fabricar de Productos Semi-elaborados.</p>

<br />

<h4>Paso 3 :: Ajustar las cantidades calculadas al Tamaño de Lote de Fabricación</h4>

<br />

<h4>Paso 4 :: Crear y Lanzar las Ordenes de Fabricación</h4>

<br />



<br />

<h3>Hoja Tipo: Reaprovisionar Almacén</h3>

<br />

<h4>Paso 0 :: Calcular las necesidades derivadas de los Requerimientos de Producción</h4>
<p>a) Requerimientos brutos: calcular las necesidades de Semi-elaborados derivadas de los Requerimientos de Producción.</p>
<p>b) Requerimientos netos: se descuenta el stock disponible (físico) de la cantidad a fabricar calculada en el punto anterior. Si no hay suficiente, se generan las Ordenes de Fabricación necesarias.</p>
<p>c) NOTA Requerimientos netos: las cantidades en los Requerimientos de Producción se fabrican integramente, sin descontar stock (si lo hubiera). El stock SOLO se descuenta de los Semi-elaborados necesarios para fabricar las cantidades de los Requerimientos de Producción.</p>

<br />

<h4>Paso 1 :: Calcular las necesidades de Productos Terminados</h4>
<p>a) Requerimientos brutos: agrupar las Líneas de Pedidos de Clientes (Productos con Aprovisionamiento: Fabricación). Normalmente los Pedidos son del "Cliente Almacén" (Cliente ficticio), y los Productos con <i>Planificación</i> = <strong>"Punto de Pedido"</strong>.</p>
<p>b) Requerimientos netos: Productos con <i>Planificación</i> = <strong>"Bajo Pedido"</strong>, NO se descuenta el stock disponible (físico) de la cantidad a fabricar (si lo hubiera). En este tipo de Hoja no deberían aparecer estos Productos.</p>
<p>c) Requerimientos netos: Productos con <i>Planificación</i> = <strong>"Punto de Pedido"</strong>, NO se descuenta el stock disponible (físico) de la cantidad a fabricar (si lo hubiera).</p>

<br />

<h4>Paso 2 :: Calcular las necesidades de Productos Semi-elaborados</h4>
<p>a) Agrupar los requerimientos de Semi-elaborados (Productos con Aprovisionamiento: Semi-Elaborado).</p>
<!-- p>b) Requerimientos netos: se descuenta el stock disponible (físico) de la cantidad a fabricar.</p -->

<br />

<h4>Paso 3 :: Ajustar las cantidades calculadas al Tamaño de Lote de Fabricación</h4>

<br />

<h4>Paso 4 :: Crear y Lanzar las Ordenes de Fabricación</h4>

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




