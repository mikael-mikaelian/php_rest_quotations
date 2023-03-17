<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    $uri = $_SERVER['REQUEST_URI'];

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }


    if ($method === 'GET' && !empty(parse_url($uri, PHP_URL_QUERY))) {
        require 'read_single.php';
    } elseif ($method === 'GET') {
        require 'read.php';
    }

    else if ($method === 'POST') {
        require('create.php');
    }

    else if ($method === 'PUT') {
        require('update.php');
    }

    else if ($method === 'DELETE') {
        require('delete.php');
    }
?>