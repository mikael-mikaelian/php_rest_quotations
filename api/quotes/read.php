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


$quote = new Quote($db);

$result = $quote->read();

$num = $result->rowCount();

if($num > 0) {
    $quotes_arr = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $quote_item = array (
            'id' => $id,
            'quote' => $quote,
            'author' => $author,
			      'category' => $category
        );

        //Push to "data"
        array_push($quotes_arr, $quote_item);
    }

    //Turn to JSON and output

    echo json_encode($quotes_arr);
} else {
    //No Categories
    echo json_encode (array('message' => 'No Quotes Found'));
}
?>