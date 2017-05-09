<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'name'     => 'test',
    ),
    'mongo' => array(
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'name'     => 'ragnar',
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir' => __DIR__ . '/../models/',
        'viewsDir' => __DIR__ . '/../views/',
        'phpExcel' =>   'plugins/PHPExcel-1.8/Classes/',
    ),
    'defaultLanguage' => 'hu'
));
