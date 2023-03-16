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


$category = new category($db);

$result = $category->read();

$num = $result->rowCount();

if($num > 0) {
    $categories_arr = array();
    $categories_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $category_item = array (
            'id' => $id,
            'category' => $category
        );

        //Push to "data"
        array_push($categories_arr['data'], $category_item);
    }

    //Turn to JSON and output

    echo json_encode($categories_arr);

} else {
    //No Categories
    echo json_encode (array('message' => 'category_id Not Found'));
}
?>