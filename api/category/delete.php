<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
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

// Set ID to update

$category->id = $data->id;

//Delete category
if ($category->delete()) {
    echo json_encode (
        array('message' => 'Category Deleted')
    );

} else {
    echo json_encode(
        array('message' => 'Category Not Deleted')
    );
}
?>