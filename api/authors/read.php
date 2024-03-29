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

$result = $author->read();

$num = $result->rowCount();

if($num > 0) {
    $authors_arr = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $author_item = array (
            'id' => $id,
            'author' => $author
        );

        //Push to "data"
        array_push($authors_arr, $author_item);
    }

    //Turn to JSON and output

    echo json_encode($authors_arr);

} else {
    //No Authors
    echo json_encode (array('message' => 'author_id Not Found'));
}
?>