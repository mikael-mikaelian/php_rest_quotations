<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

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

// Instantiate Quote obgect
$quote = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

if (isset($data->quote)){
$quote->quote = $data->quote;
}
if (isset($data->author_id)){
$quote->author_id = $data->author_id;
}
if (isset($data->category_id)){
$quote->category_id = $data->category_id;
}

//Create Quote
$result = $quote->create();
echo ($result);

?>