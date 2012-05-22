<?php
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'database'=>array(
        'host' => DB_HOST,
        'port' => DB_PORT,
        'database' => DB_DATABASE,
        'username' => DB_USERNAME,
        'password' => DB_PASSWORD,
        'charset' => UTF8_CHARSET,
    ),
    'extensions'=>array(
        'storage'=>array(
            'class'=>'ext.storage',
        )
    ),
);
?>