<?php
class Author {
    // DB
    private $connection;
    private $table = 'authors';

    //Author properties
    public $id;
    public $author;

    public function __construct($db) {
        $this->connection = $db;
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

    // Get single author

    public function read_single() {
        // Create query
        $query = 'SELECT 
                id,
                author
            FROM
                ' . $this->table . '
            WHERE
                id = ?
        ';


        //Prepare statement
        $statement = $this->connection->prepare($query);

        //Bind ID
        $statement->bindParam(1, $this->id);

        //Execute query
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        //Set properties
        $this->author = $row['author'];


        return $statement;
    }

    //Create author
    public function create() {
        
        if (!isset($this->author)) {
            return "Author is not set.";
        }

        //create query
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';
        
        //Prepare statement
        $statement = $this->connection->prepare($query);
    
        // Clean data
        $this->author = htmlspecialchars(strip_tags($this->author));
    
        // Bind data
        $statement->bindParam(':author', $this->author);

        //Ececute query
        if ($statement->execute()) {
            return true;
        } else {
            return $statement->errorInfo();
        }
    }

    //Update author
    public function update() {
        
        if (!isset($this->author)) {
            return "Author is not set.";
        }

        //create query
        $query = 'UPDATE ' .
				$this->table . '
			SET
				author = :author
			WHERE
				id = :id';
        
        //Prepare statement
        $statement = $this->connection->prepare($query);
    
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->author = htmlspecialchars(strip_tags($this->author));
    
        // Bind data
        $statement->bindParam(':author', $this->author);
        $statement->bindParam(':id', $this->id);


        //Ececute query
        if ($statement->execute()) {
            return true;
        } else {
            return $statement->errorInfo();
        }
    }


    //Delete author

    public function delete() {
        // Create query
        $query = 'DELETE FROM ' .
				$this->table .
			' WHERE id = :id';

        //Prepare statement
        $statement = $this->connection->prepare($query);

        // Clear data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $statement->bindParam(':id', $this->id);

        //Ececute query
        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
        
    }


}
?>