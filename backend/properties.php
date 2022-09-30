<?php

require_once 'bootstrap.php';

use App\Services\Properties\PropertiesService;

$properties = new PropertiesService($connection);

$allPages = $properties->getTotalPages();

$page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;

$perPage = 5;
$totalPages = ceil($allPages['total'] / $perPage);
$start = 0;
$prev = $page - 1;
$next = $page + 1;

if($page < $totalPages){
    $start = $perPage * ($page - 1);
}
$listData = $properties->all($start,$perPage);

$listInfo = json_decode($listData);

echo $listData;