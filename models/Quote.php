<?php
class Quote {
    // DB
    private $connection;
    private $table = 'quotes';

    //Author properties
    public $id;
    public $quote;
    public $author;
    public $category;
    public $author_id;
    public $category_id;

    public function __construct($db) {
        $this->connection = $db;
    }

    // Get Authors
    public function read() {
        // Create query
        $query = 'SELECT
				quotes.id,
				quotes.quote,
				authors.author,
				categories.category
			FROM
				' . $this->table . '
			INNER JOIN
				authors
			ON
				quotes.author_id = authors.id
			INNER JOIN
				categories
			ON
				quotes.category_id = categories.id
			ORDER BY
				id';

        $statement = $this->connection->prepare($query);

        $statement->execute();

        return $statement;

    }

    // Get single quote
    public function read_single() {

        //GET by id
        if (isset($_GET['id'])) {

            //set id
            $this->id = $_GET['id'];

            // Create query
            $query = 'SELECT
                quotes.id,
                quotes.quote,
                authors.author,
                categories.category
            FROM
                ' . $this->table . '
            INNER JOIN
                authors
            ON
                quotes.author_id = authors.id
            INNER JOIN
                categories
            ON
                quotes.category_id = categories.id
            WHERE
                quotes.id = :id
            ORDER BY
                quotes.id';
        
            //Prepate query
            $statement = $this->connection->prepare($query);

            //Bind id
            $statement->bindParam(':id', $this->id);

            //Execute query
            $statement->execute();
            
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            
            //Set properties
            if (is_array($row)) {
                $this->quote = $row['quote'];
                $this->author = $row['author'];
                $this->category = $row['category'];
            }
            
        }
        
        if (isset($_GET['category_id']) && isset($_GET['author_id'])) {
            //set category_id and author_id
            $this->category_id = $_GET['category_id'];
            $this->author_id = $_GET['author_id'];

            // Create query
            $query = 'SELECT
                quotes.id,
                quotes.quote,
                authors.author,
                categories.category
            FROM
                ' . $this->table . '
            INNER JOIN
                authors
            ON
                quotes.author_id = authors.id
            INNER JOIN
                categories
            ON
                quotes.category_id = categories.id
            WHERE
                quotes.category_id = :category_id AND quotes.author_id = :author_id
            ORDER BY
                quotes.id';

            //Prepate query
            $statement = $this->connection->prepare($query);

            //Bind category_id and author_id
            $statement->bindParam(':category_id', $this->category_id);
            $statement->bindParam(':author_id', $this->author_id);

            //Execute query
            $statement->execute();

            //Create quotes array
            $quotes = [];

			while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
				extract($row);
				$quotes[] = [
					'id' => $id,
					'quote' => $quote,
					'author' => $author,
					'category' => $category
				];
			}
            
			return $quotes;
        }


        //GET by author_id
        if (isset($_GET['author_id'])) {
            
            //Set author_id
            $this->author_id = $_GET['author_id'];

            //Create query
            $query = 'SELECT
                quotes.id,
                quotes.quote,
                authors.author,
                categories.category
            FROM
                ' . $this->table . '
            INNER JOIN
                authors
            ON
                quotes.author_id = authors.id
            INNER JOIN
                categories
            ON
                quotes.category_id = categories.id
            WHERE
                quotes.author_id = :author_id
            ORDER BY
                quotes.id';
        
            //Prepare query
            $statement = $this->connection->prepare($query);

            //Bind author_id
            $statement->bindParam(':author_id', $this->author_id);

            //Execute query
            $statement->execute();
            
            //Create quotes array
            $quotes = [];

			while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
				extract($row);
				$quotes[] = [
					'id' => $id,
					'quote' => $quote,
					'author' => $author,
					'category' => $category
				];
			}

			return $quotes;
        }

        //GET by category_id
        if (isset($_GET['category_id'])) {

            //set category_id
            $this->category_id = $_GET['category_id'];

            // Create query
            $query = 'SELECT
                quotes.id,
                quotes.quote,
                authors.author,
                categories.category
            FROM
                ' . $this->table . '
            INNER JOIN
                authors
            ON
                quotes.author_id = authors.id
            INNER JOIN
                categories
            ON
                quotes.category_id = categories.id
            WHERE
                quotes.category_id = :category_id
            ORDER BY
                quotes.id';
        
            //Prepate query
            $statement = $this->connection->prepare($query);

            //Bind id
            $statement->bindParam(':category_id', $this->category_id);

            //Execute query
            $statement->execute();
            
            //Create quotes array
            $quotes = [];

			while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
				extract($row);
				$quotes[] = [
					'id' => $id,
					'quote' => $quote,
					'author' => $author,
					'category' => $category
				];
			}
            
			return $quotes;
            
        }
    }

    //Create author
    public function create() {
        
        if (!isset($this->category_id) || !isset($this->author_id) || !isset($this->quote)) {
            return json_encode(array(
                'message' => 'Missing Required Parameters',
            ));
        }

        // Check if author_id exists
        $author_query = 'SELECT id FROM authors WHERE id = :author_id';
        $author_statement = $this->connection->prepare($author_query);
        $author_statement->bindParam(':author_id', $this->author_id);
        $author_statement->execute();
        $author_result = $author_statement->fetch(PDO::FETCH_ASSOC);

        // Check if category_id exists
        $category_query = 'SELECT id FROM categories WHERE id = :category_id';
        $category_statement = $this->connection->prepare($category_query);
        $category_statement->bindParam(':category_id', $this->category_id);
        $category_statement->execute();
        $category_result = $category_statement->fetch(PDO::FETCH_ASSOC);


        //Return Error Messages

        if (!$author_result && !$category_result) {
            return json_encode(array(
                array('message' => 'author_id Not Found'),
                array('message' => 'category_id Not Found')
            ));
        } else if (!$author_result) {
            return json_encode(array(
                'message' => 'author_id Not Found',
            ));
        } else if (!$category_result) {
            return json_encode(array(
                'message' => 'category_id Not Found'
            ));
        }

        //create query
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';
        
        //Prepare statement
        $statement = $this->connection->prepare($query);
    
        // Clean data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
    
        // Bind data
        $statement->bindParam(':quote', $this->quote);
        $statement->bindParam(':author_id', $this->author_id);
        $statement->bindParam(':category_id', $this->category_id);

        //Ececute query
        if ($statement->execute()) {
            $this->id = $this->connection->lastInsertId();
            $result = 
                json_encode (
                    array(
                        'id' => $this->id,
                        'quote' => $this->quote,
                        'author_id' => $this->author_id,
                        'category_id' => $this->category_id
                    )
                );
            return $result;
        } else {
            return $statement->errorInfo();
        }
    }

    //Update author
    public function update() {
        
        if (!isset($this->category_id) || !isset($this->author_id) || !isset($this->quote)) {
            return json_encode(array(
                'message' => 'Missing Required Parameters',
            ));
        }

      // Check if id exists
        $id_query = 'SELECT id FROM quotes WHERE id = :id';
        $id_statement = $this->connection->prepare($id_query);
        $id_statement->bindParam(':id', $this->id);
        $id_statement->execute();
        $id_result = $id_statement->fetch(PDO::FETCH_ASSOC);
      
        // Check if author_id exists
        $author_query = 'SELECT id FROM authors WHERE id = :author_id';
        $author_statement = $this->connection->prepare($author_query);
        $author_statement->bindParam(':author_id', $this->author_id);
        $author_statement->execute();
        $author_result = $author_statement->fetch(PDO::FETCH_ASSOC);

        // Check if category_id exists
        $category_query = 'SELECT id FROM categories WHERE id = :category_id';
        $category_statement = $this->connection->prepare($category_query);
        $category_statement->bindParam(':category_id', $this->category_id);
        $category_statement->execute();
        $category_result = $category_statement->fetch(PDO::FETCH_ASSOC);


        //Return Error Messages

        if (!$author_result) {
            return json_encode(array(
                'message' => 'author_id Not Found',
            ));
        } else if (!$category_result) {
            return json_encode(array(
                'message' => 'category_id Not Found'
            ));
        } else if (!$id_result) {
            return json_encode(array(
                'message' => 'No Quotes Found'
            ));
        }
      
        //create query
        $query = 'UPDATE ' .
				$this->table . '
			SET
                quote = :quote,
                author_id = :author_id,
                category_id = :category_id
			WHERE
				id = :id';
        
        //Prepare statement
        $statement = $this->connection->prepare($query);
    
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data
        $statement->bindParam(':quote', $this->quote);
        $statement->bindParam(':author_id', $this->author_id);
        $statement->bindParam(':category_id', $this->category_id);
        $statement->bindParam(':id', $this->id);


        //Ececute query
        if ($statement->execute()) {
            return json_encode(
              array(
                        'id' => $this->id,
                        'quote' => $this->quote,
                        'author_id' => $this->author_id,
                        'category_id' => $this->category_id
                    )
            );
        } else {
            echo($statement->errorInfo());
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
                'message' => 'No Quotes Found',
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
        $id_query = 'SELECT id FROM quotes WHERE id = :id';
        $id_statement = $this->connection->prepare($id_query);
        $id_statement->bindParam(':id', $this->id);
        $id_statement->execute();
        $id_result = $id_statement->fetch(PDO::FETCH_ASSOC);

        if (!$id_result) {
            return true;
        }

        return false;
      }


}
?>