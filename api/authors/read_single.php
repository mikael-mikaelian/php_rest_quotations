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
require_once '../../models/Author.php';


$database = new Database();
$db = $database->connect();


$author = new Author($db);

//Get ID
$author->id = isset($_GET['id']) ? $_GET['id'] : die();


//Get author
if ($author->read_single()) {

    // Create array

    $author_arr = array (
    'id' => $author->id,
    'author' => $author->author,
    );

    //Convert to JSON
    print_r(json_encode($author_arr));

} else {
    echo json_encode (array('message' => 'author_id Not Found'));
}

?>