<?php
// Autoload files using the Composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use App\Config\Connection;
use App\Services\Properties\PropertiesService;
use App\Services\PropertyTypes\PropertyTypesService;

$evn_file  = 'config.env';

$dotenv = Dotenv::createImmutable(__DIR__, $evn_file);
$dotenv->load();
$connection = new Connection();