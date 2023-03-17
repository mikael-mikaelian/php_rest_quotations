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
require_once '../../models/Category.php';


$database = new Database();
$db = $database->connect();


$category = new Category($db);

//Get ID
$category->id = isset($_GET['id']) ? $_GET['id'] : die();


//Get category
if ($category->read_single()) {

    // Create array
    $category_arr = array (
        'id' => $category->id,
        'category' => $category->category,
    );

    //Convert to JSON
    print_r(json_encode($category_arr));
} else {
    echo json_encode (array('message' => 'category_id Not Found'));
}
?>