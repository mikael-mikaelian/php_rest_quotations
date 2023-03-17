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
        if (is_array($row)) {
            $this->author = $row['author'];
            return $statement;
        } else {
            return false;
        }
    }

    //Create author
    public function create() {
        
        if (!isset($this->author)) {
            return false;
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
            $this->id = $this->connection->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    //Update author
    public function update() {
        
        if (!isset($this->author) || !isset($this->id)) {
            return json_encode(array(
                'message' => 'Missing Required Parameters',
            ));
        }

        // Check if id exists
        if($this->idIsNotExist()) {
            return json_encode(array(
                'message' => 'author_id Not Found',
            ));
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
            return json_encode(array(
                    'id' => $this->id,
                    'author' => $this->author
                )
            );
        } else {
            return $statement->errorInfo();
        }
    }


    //Delete author
    public function delete() {
        
        if (!isset($this->id)) {
            return json_encode(array(
                'message' => 'Missing Required Parameters',
            ));
        }

        if($this->idIsNotExist()) {
            return json_encode(array(
                'message' => 'author_id Not Found',
            ));
        }

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
            return json_encode(array(
                'id' => $this->id
            ));
        } else {
            echo($statement->errorInfo());
        }
        
    }

    private function idIsNotExist() {
        $author_query = 'SELECT id FROM authors WHERE id = :id';
        $author_statement = $this->connection->prepare($author_query);
        $author_statement->bindParam(':id', $this->id);
        $author_statement->execute();
        $author_result = $author_statement->fetch(PDO::FETCH_ASSOC);

        if (!$author_result) {
            return true;
        }

        return false;
    }
}
?>