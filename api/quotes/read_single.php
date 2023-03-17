<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}


require_once '../../config/Database.php';
require_once '../../models/Quote.php';


$database = new Database();
$db = $database->connect();


$quotes = new Quote($db);

if (isset($_GET['id'])) {
    $quotes->read_single();

    $quotes_arr = array(
        'id' => $quotes->id,
        'quote' => $quotes->quote,
        'author' => $quotes->author,
        'category' => $quotes->category
    );

    if($quotes->quote !== null) {
        //Change to JSON data
        print_r(json_encode($quotes_arr, JSON_NUMERIC_CHECK));

    } else {
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
} else {
    $quotes_arr = $quotes->read_single();

    if(!empty($quotes_arr)){
        //Change to JSON data
        print_r(json_encode($quotes_arr, JSON_NUMERIC_CHECK));

    } else {
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }

}


?>