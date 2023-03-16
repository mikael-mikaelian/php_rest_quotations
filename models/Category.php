<?php
class category {
    // DB
    private $connection;
    private $table = 'categories';

    //category properties
    public $id;
    public $category;

    public function __construct($db) {
        $this->connection = $db;
    }

    // Get categorys
    public function read() {
        // Create query
        $query = 'SELECT 
                id,
                category
            FROM
                ' . $this->table . '
        ';

        $statement = $this->connection->prepare($query);

        $statement->execute();

        return $statement;

    }

    // Get single Category

    public function read_single() {
        // Create query
        $query = 'SELECT 
                id,
                category
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
        $this->category = $row['category'];

        if ($this->category !== null) {
            return $statement;
        } else {
            return false;
        }

    }

    //Create category
    public function create() {
        
        if (!isset($this->category)) {
            return false;
        }

        //create query
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';
        
        //Prepare statement
        $statement = $this->connection->prepare($query);
    
        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));
    
        // Bind data
        $statement->bindParam(':category', $this->category);

        //Ececute query
        if ($statement->execute()) {
            $this->id = $this->connection->lastInsertId();
            return true;
        } else {
            return $statement->errorInfo();
        }
    }

    //Update Category
    public function update() {
        
        if (!isset($this->category) || !isset($this->id)) {
            return json_encode(array(
                'message' => 'Missing Required Parameters'
            ));
        }

        // Check if id exists
        if(idIsNotExit){
            return json_encode(array(
                'message' => 'category_id Not Found',
            ));
        }

        //create query
        $query = 'UPDATE ' .
				$this->table . '
			SET
				category = :category
			WHERE
				id = :id';
        
        //Prepare statement
        $statement = $this->connection->prepare($query);
    
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->category = htmlspecialchars(strip_tags($this->category));
    
        // Bind data
        $statement->bindParam(':category', $this->category);
        $statement->bindParam(':id', $this->id);


        //Ececute query
        if ($statement->execute()) {
            return json_encode(array(
                'id' => $this->id,
                'category' => $this->category
            ));
        } else {
            return $statement->errorInfo();
        }
    }


    //Delete category

    public function delete() {

        if (!isset($this->id)) {
            return json_encode(array(
                'message' => 'Missing Required Parameters'
            ));
        }

        return json_encode(array(
            'message' => 'category_id Not Found',
        ));

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
                'id of deleted category' => $this->id
            ));
        } else {
            echo($statement->errorInfo());
        }
        
    }

    private function idIsNotExit(){
        $category_query = 'SELECT id FROM categories WHERE id = :id';
        $category_statement = $this->connection->prepare($category_query);
        $category_statement->bindParam(':id', $this->id);
        $category_statement->execute();
        $category_result = $category_statement->fetch(PDO::FETCH_ASSOC);

        if (!$category_result) {
            return true;
        }

        return false;

    }
}
?>