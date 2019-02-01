<?php

return array(


    /*'pdf' => array(
        'enabled' => true,
        'binary'  => '/usr/local/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),*/
    /*'pdf' => array(
        'enabled' => true,
        'binary'  => 'xvfb-run /usr/local/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),*/
    'pdf' => array(
        'enabled' => true,
	//'binary' => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),
        'binary' => 'xvfb-run /usr/local/bin/wkhtmltopdf',
        'timeout' => 3600,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => '/usr/local/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
