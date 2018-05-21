<?php
require_once "LoadLandingStatic/v1.0/LoadLandingStatic.php";

$download=new LoadLandingStatic();

$download->fileHTML='index.html';
$download->params=[
        ['extension'=>'js' ,'path_save'=>'js',         'name_save'=>true],
//        ['extension'=>'css','path_save'=>'css',        'name_save'=>true],
//        ['extension'=>'jpg','path_save'=>'images/jpg', 'name_save'=>false],
//        ['extension'=>'png','path_save'=>'images/png', 'name_save'=>false],
//        ['extension'=>'gif','path_save'=>'images/gif', 'name_save'=>false],
    ];

$download->run();
