<?php

return [

    'title' => 'Installation Assistant :: aBillander',

    'overview' => [
        'welcome' => 'Choose your language',
        'license' => 'License Agreements',
        'requirements' => 'System compatibility',
        'config' => 'Configuration',
        'mail' => 'Email Settings',
        'install' => 'Installation',
        'company' => 'Company',
        'done' => 'Installation completed',
    ],

    'welcome' => [
        'title' => 'Welcome to the aBillander Installer',
        'body' => '',
        'select_lang' => 'Continue the installation in:',
    ],

    'license' => [
        'title' => 'License Agreements',
        'body' => 'To enjoy the many features that are offered by aBillander, please read the license terms below. ',
        'accept' => 'I agree to the above terms and conditions.',

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
        'title' => 'System compatibility',
        'body' => 'If you find any problem, please ask your Hosting Provider or System Administrator.',
        'server' => 'Server Requerimients',
        'folders' => 'Permissions of Folders',
        'php' => 'min. version :ver',
        'error' => 'Some errors have been found. You must resolve them before continuing.',
    ],

    'config' => [
        'title' => 'Configuration',
        'body' => 'To use aBillander, you must create a database to collect all of your business data-related activities.<br /><br />Please fill in these details so that aBillander can connect to your database.',
        'database' => 'Configure your database by filling out the following fields',
        'host' => 'Database host',
        'port' => 'Database port',
        'port_help' => 'Default port is 3306.',
        'name' => 'Database name',
        'login' => 'Database login',
        'password' => 'Database password',
        'check' => 'Test Database connection now!',
        'check_ok' => 'The database configuration is correct.',
        'check_ko' => 'Cannot connect to Database. Check the following errors:',
    ],

    'mail' => [
        'title' => 'Email Configuration',
        'body' => 'Please fill in these details so that aBillander can send emails for you.<br /><br />These email settings can be changed later by editing the "<i> .env </i>" file.',
        'mailhost' => 'Configure your Mail Host by filling out the following fields',
        'driver' => 'Host type',
        'driver_help' => 'Advanced users may change this by editing the "<i>.env</i>" file.',
        'host' => 'Mail Host',
        'port' => 'Mail Host port',
        'port_help' => 'Usually 465 or 587.',
        'encryption' => 'Encryption method',

        'user' => 'Host Username',
        'password' => 'Host Password',

        'from' => 'From email',
        'from_help' => 'The email address which emails are sent from.<br />If you are using an email provider (Gmail, Yahoo, Outlook.com, etc.) this should be your email address for that account.',
        'from_name' => 'From name',
        'from_name_help' => 'The name which emails are sent from.',

        'check' => 'Send a test email',
        'subject' => ' :_> Email sent by aBillander',
        'message' => 'aBillander is ready to send email.',
        'check_ok' => 'Your email has been sent!',
        'check_ko' => 'There was an error, your message could not be sent. Check the following errors:',
    ],

    'install' => [
        'title' => 'Installation',
        'body' => 'Database tables will be created, and aBillander will be set ready for use.<br /><br />This operation may take a few minutes; you must not interrupt it.',
        'database_not_empty' => 'Your Database contains tables. You should delete them before continuing.',
        'success' => 'aBillander configuration is correct and it is already working.',

        'install' => 'Install',
    ],

    'company' => [
        'title' => 'Company',
        'body' => '',
    ],

    'done' => [
        'title' => 'Installation completed',
        'body' => 'Please, remember your login information:',
        'success' => 'You have just finished installing your System. Thank you for using aBillander!',
        'link' => 'Go to main page',
    ],

];
