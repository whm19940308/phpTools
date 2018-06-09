<?php

require_once './_autoload.php';
require_once './functions.php';



$array = new ArrayTools();

$arr = array(
    array(
        'id' => 1,
        'name' => 'sb'
    ),
    array(
        'id' => 2,
        'name' => 'sb2'
    ),
);

$data = $array->convertArrKey($arr, 'name');

dump($data);
dd($data);
