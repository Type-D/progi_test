<?php
declare(strict_types=1);

require_once __DIR__ . "/Controller/Api/BaseController.php";
require_once __DIR__ . "/Controller/Api/CostCalculatorController.php";
require_once __DIR__ . "/Model/CostCalculatorModel.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// For now, only cost-calculator exists
if (isset($uri[1]) && $uri[1] != 'cost-calculator') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$objController = new CostCalculatorController();
$objController->{$uri[2]}();
?>