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
require_once '../../models/Category.php';


$database = new Database();
$db = $database->connect();

// Instantiate athor obgect
$category = new Category($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$category->category = $data->category;

//Create Category
if ($category->create()) {
    echo json_encode (
        array('category created' => array(
            'id' => $category->id,
            'category' => $category->category
        ))
    );

} else {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
?>