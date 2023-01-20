<?php

session_start();

use Maggsweb\MyPDO;

require __DIR__.'/../vendor/autoload.php';

$settings = parse_ini_file( 'configuration.ini');
//dump($settings);

$maxUploadFilesize = ini_get('upload_max_filesize');
$maxPostFilesize = ini_get('post_max_size');


$db = new MyPDO($settings['host'], $settings['user'], $settings['pass'], $settings['dbname']);
if($db->getError()) {
    dump($db->getError());
    exit;
}

//if (!is_dir($settings['upload_dir'])) {
//    mkdir($settings['upload_dir'], 0777);
//}

$results = $db->query('SHOW TABLES')->fetchAll('Array');
foreach($results as $result) {
    foreach($result as $table) {
        $db_tables[] = $table;
    }
}
//dump($db_tables);