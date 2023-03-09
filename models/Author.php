<?php
class Author {
    // DB
    private $connection;
    private $table = 'authors';

    //Author properties
    public $id;
    public $author;

    public function __construct($db) {
        $this->connection = $db
    }

    // Get Authors
    public function read() {
        // Create query
        $query = 'SELECT 
                id,
                author
            FROM
                ' . $this->table . '
        ';

        $statement = $this->connection->prepare($query);

        $statement->execute();

        return $statement;

    }
}
?>