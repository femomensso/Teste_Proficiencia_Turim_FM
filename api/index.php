<?php

require "pessoa.php";
require "bd.php";
require "controller.php";

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");

$bd=new bd();
$pessoa = new Pessoa();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );


if ($uri[1] !== 'api') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
$controller = new Controller($pessoa, $bd, $requestMethod);
$controller->processRequest();


?>