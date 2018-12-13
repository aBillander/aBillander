
@section('modals')    @parent

<div class="modal fade" id="customercenterHelp" tabindex="-1" role="dialog" aria-labelledby="myLaraBillander" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myLaraBillander">Clientes</h4>
            </div>
            <div class="modal-body">


<h2>Acceso al Centro de Clientes</h2>
<p>Para que un Cliente pueda acceder al Centro de Clientes es necesario que:</p>

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




{{--
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
