<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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

// Instantiate athor obgect
$quote = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set ID to update

$quote->id = isset($data->id) ? $data->id : null;

$quote->quote = isset($data->quote) ? $data->quote : null;

$quote->author_id = isset($data->author_id) ? $data->author_id : null;

$quote->category_id = isset($data->category_id) ? $data->category_id : null;


//Update category
$result = $quote->update();
echo($result);
?>