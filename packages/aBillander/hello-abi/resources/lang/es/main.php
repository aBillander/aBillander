<?php

return [

    'title' => 'Asistente de instalación :: aBillander',

    'overview' => [
        'welcome' => 'Seleccionar Idioma',
        'license' => 'Acuerdo de Licencia',
        'requirements' => 'Compatibilidad del Sistema',
        'config' => 'Configuración',
        'mail' => 'Correo Electrónico',
        'install' => 'Instalación',
        'company' => 'Información de la Empresa',
        'done' => 'Instalación terminada',
    ],

    'welcome' => [
        'title' => 'Bienvenido al asistente de instalación de aBillander',
        'body' => '',
        'select_lang' => 'Continuar la instalación en:',
    ],

    'license' => [
        'title' => 'Acuerdo de Licencia',
        'body' => 'Para disfrutar de las numerosas funcionalidades ofrecidas por aBillander, por favor lea los términos de la licencia a continuación.',
        'accept' => 'Estoy de acuerdo con los términos de la Licencia.',

        'license' => '
                    <p>Lorem ipsum dolor sit amet, veniam numquam has te. No suas nonumes recusabo mea, est ut graeci definitiones. His ne melius vituperata scriptorem, cum paulo copiosae conclusionemque at. Facer inermis ius in, ad brute nominati referrentur vis. Dicat erant sit ex. Phaedrum imperdiet scribentur vix no, ad latine similique forensibus vel.</p>
                    <p>Dolore populo vivendum vis eu, mei quaestio liberavisse ex. Electram necessitatibus ut vel, quo at probatus oportere, molestie conclusionemque pri cu. Brute augue tincidunt vim id, ne munere fierent rationibus mei. Ut pro volutpat praesent qualisque, an iisque scripta intellegebat eam.</p>
                    <p>Mea ea nonumy labores lobortis, duo quaestio antiopam inimicus et. Ea natum solet iisque quo, prodesset mnesarchum ne vim. Sonet detraxit temporibus no has. Omnium blandit in vim, mea at omnium oblique.</p>
                    <p>Eum ea quidam oportere imperdiet, facer oportere vituperatoribus eu vix, mea ei iisque legendos hendrerit. Blandit comprehensam eu his, ad eros veniam ridens eum. Id odio lobortis elaboraret pro. Vix te fabulas partiendo.</p>
                    <p>Natum oportere et qui, vis graeco tincidunt instructior an, autem elitr noster per et. Mea eu mundi qualisque. Quo nemore nusquam vituperata et, mea ut abhorreant deseruisse, cu nostrud postulant dissentias qui. Postea tincidunt vel eu.</p>
                    <p>Ad eos alia inermis nominavi, eum nibh docendi definitionem no. Ius eu stet mucius nonumes, no mea facilis philosophia necessitatibus. Te eam vidit iisque legendos, vero meliore deserunt ius ea. An qui inimicus inciderint.</p>
                    ',
    ],

    'requirements' => [
        'title' => 'Compatibilidad del Sistema',
        'body' => 'A continuación se muestran los requerimientos de instalación de la aplicación. Si existiera alguna incompatibilidad consulte con su proveedor de hosting o administrador.',
        'server' => 'Requerimientos del Servidor',
        'folders' => 'Permisos de las Carpetas',
        'php' => 'min. :ver',
        'error' => 'Se han encontrado errores. Debe resolverlos antes de continuar.',
    ],

    'config' => [
        'title' => 'Configuración',
        'body' => 'Para usar aBillander, usted debe crear una base de datos para recolectar todas las actividades relacionadas con información de su negocio.<br /><br />Por favor, rellene estos datos con el fin de que aBillander pueda conectarse a su base de datos.',
        'database' => 'Configure su Base de Datos rellenando los campos siguientes',
        'host' => 'Servidor de la base de datos',
        'port' => 'Puerto',
        'port_help' => 'El Puerto por defecto es 3306.',
        'name' => 'Nombre de la base de datos',
        'login' => 'Nombre de usuario',
        'password' => 'Contraseña',
        'check' => 'Probar la conexión a la base de datos ahora',
        'check_ok' => 'La configuración de la base de datos es correcta.',
        'check_ko' => 'No se puede conectar con la base de datos. Compruebe los siguientes errores:',
    ],

    'mail' => [
        'title' => 'Configuración del Correo Electrónico',
        'body' => 'Por favor, rellene estos datos con el fin de que aBillander pueda enviar correo electrónico.<br /><br />La configuración del correo electrónico podrá cambiarse más adelante editando el fichero "<i>.env</i>".',
        'mailhost' => 'Configure su Servidor de Correo rellenando los campos siguientes',
        'driver' => 'Tipo de Servidor',
        'driver_help' => 'Usuarios avanzados pueden cambiar esta configuración editando el fichero "<i>.env</i>".',
        'host' => 'Servidor de Correo',
        'port' => 'Puerto',
        'port_help' => 'Normalmente 465 o 587.',
        'encryption' => 'Método de encriptado',

        'user' => 'Usuario',
        'password' => 'Contraseña',

        'from' => 'Email del remitente',
        'from_help' => 'Es la dirección de correo que aparece en los correos electrónicos enviados.<br />Si está usando un proveedor decorreo electrónico (Gmail, Yahoo, Outlook.com, etc.) debe poner aquí la dirección de correo de su cuenta.',
        'from_name' => 'Nombre del remitente',
        'from_name_help' => 'El nombre que aparece en los correos electrónicos enviados.',

        'check' => 'Enviar un correo de prueba',
        'subject' => ' :_> Email enviado por aBillander',
        'message' => 'aBillander está listo para enviar correo electrónico.',
        'check_ok' => 'Su email ¡ha sido enviado!',
        'check_ko' => 'Se ha producido un error, su mensaje no pudo enviarse. Compruebe los siguientes errores:',
    ],

    'install' => [
        'title' => 'Instalación',
        'body' => 'Se crearán las tablas en la Base de Datos y aBillander estará listo para usarse.<br /><br />Esta operación puede durar algunos minutos; no debe interrumpirla.',
        'database_not_empty' => 'La Base de Datos contiene tablas. Debería borrarlas antes de continuar.',
        'success' => 'La configuración de aBillander es correcta y ya está funcionando.',

        'install' => 'Instalar',
    ],

    'company' => [
        'title' => 'Información de la Empresa',
        'body' => '',
    ],

    'done' => [
        'title' => 'Instalación terminada',
        'body' => 'Por favor, recuerde sus datos de acceso:',
        'success' => 'Acaba de terminar la instalación de su Sistema. ¡Gracias por utilizar aBillander!',
        'link' => 'Ir a la página principal',
    ],

];
