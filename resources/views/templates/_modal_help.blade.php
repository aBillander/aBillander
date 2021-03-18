
@section('modals')    @parent

<div class="modal fade" id="templatesHelp" tabindex="-1" role="dialog" aria-labelledby="myLaraBillander" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myLaraBillander">Plantillas PDF</h4>
            </div>
            <div class="modal-body">

<h2>Plantilla PDF</h2>
<p>Una plantilla para generar una vista en pdf consta de uno o más ficheros "Blade", todos ellos dentro de una carpeta. Uno de los ficheros debe ser el principal, y desde él se carga el resto de los ficheros según las convenciones del motor Blade.</p>
<p>El nombre del fichero principal de la plantilla debe ser: <em>&lt;nombre&gt;.blade.php</em>. El nombre de la carpeta que contiene la plantilla debe ser: <em>&lt;nombre&gt;</em>. Por ejemplo, si el nombre de la plantilla es "invoice", se tendrá:</p>
<p>
- invoice<br />
 &nbsp; &nbsp; ^-- invoice.blade.php<br />
 &nbsp; &nbsp; ^-- invoice_header.blade.php<br />
 &nbsp; &nbsp; ^-- invoice_body.blade.php<br />
 &nbsp; &nbsp; ^-- invoice_footer.blade.php<br />

</p>
<p>En este ejemplo, la plantilla se referenciará por su nombre "invoice". Desde este fichero, se incluyen (mediante los mecanismos de Blade) los parciales <em>invoice_header.blade.php</em>, <em>invoice_body.blade.php</em> y <em>invoice_footer.blade.php</em>.</p>

<p>Cada plantilla está asociada a un tipo de documento. Por eso, los ficheros de las plantillas se ubican en carpetas según el tipo de documento al que corresponden. Y todas estas carpetas se ubican en <em>resources/templates</em>.</p>

<p>
- resources<br />
 &nbsp;  &nbsp; templates<br />
 &nbsp;  &nbsp;  &nbsp; abcc<br />
 &nbsp;  &nbsp;  &nbsp;  &nbsp; customer_order<br />
 &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; default<br />
 &nbsp;  &nbsp;  &nbsp;  &nbsp;   &nbsp; ^-- default.blade.php<br />
 &nbsp;  &nbsp;  &nbsp; customer_invoices<br />
 &nbsp;  &nbsp;  &nbsp;  &nbsp; invoice<br />
 &nbsp;  &nbsp;  &nbsp;  &nbsp; ^-- invoice.blade.php<br />
 &nbsp; &nbsp;  &nbsp;  &nbsp; ^-- invoice_header.blade.php<br />
 &nbsp; &nbsp;  &nbsp;  &nbsp; ^-- invoice_body.blade.php<br />
 &nbsp; &nbsp;  &nbsp;  &nbsp; ^-- invoice_footer.blade.php<br />
</p>
<p>Nota: en la carpeta <em>resources/templates/abcc</em> se separan las plantillas específicas para el Centro de Clientes.</p>

<h2>Cómo referenciar las plantillas PDF</h2>
<p></p>
<div class="alert alert-info">
    <p><strong>// Código de ejemplo</strong></p>
    <p></p>
    <p>$document = CustomerInvoice::first();</p>
    <p></p>
    <p>$template = 'templates::customer_invoices.invoice.invoice';</p>
    <p></p>
    <p>return view( $template, compact('document') );</p>
</div>


<p><strong>templates::</strong> se define en <em>BillanderServiceProvider</em>, y apunta a <em>resources/templates</em>.</p>
<p><strong>customer_invoices</strong> es la carpeta que corresponde al tipo de documento, en este caso el que corresponde al modelo "CustomerInvoice".</p>
<p><strong>invoice</strong> es la carpeta con los ficheros de la plantilla.</p>
<p><strong>invoice</strong> es el nombre del fichero principal de la plantilla.</p>



<h2>La clase <em>Template</em></h2>
<p><strong>folder</strong> es la carpeta principal que aloja la plantilla. La ruta es relativa a la carpeta <em>resources/</em>, y se especifica según la notación "Blade". Si se deja en blanco, se tomará el valor por defecto <em>templates::</em>.</p>
<p><strong>model_name</strong> es el tipo de documento al que se asocia la plantilla.</p>
<p><strong>file_name</strong> es el nombre de la carpeta con los ficheros de la plantilla, y a la vez, el nombre fichero principal de la plantilla.</p>

<p></p>
<div class="alert alert-info">
    <p><strong>// Código de ejemplo</strong></p>
    <p></p>
    <p>$document = CustomerInvoice::first();</p>
    <p></p>
    <p>// Get Template</p>
    <p></p>
    <p>$t = $document->template ?? </p>
    <p> &nbsp;  &nbsp;  &nbsp;  &nbsp; \App\Template::find( Configuration::getInt('DEF_CUSTOMER_INVOICE_TEMPLATE') );</p>
    <p></p>
    <p>$template = $t->folder.snake_case( Str::plural( 'CustomerInvoice' ) ).'.'.$t->file_name.'.'.$t->file_name;</p>
    <p></p>
    <p>// O lo que es lo mismo (recomendado):</p>
    <p></p>
    <p>$template = $t->getPath();</p>
    <p></p>
    <p>return view( $template, compact('document') );</p>
</div>


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
