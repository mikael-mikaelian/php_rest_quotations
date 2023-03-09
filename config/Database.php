<?php
    class Database {
        private $host = 'localhost';
        private $db_name = 'quotesdb';
        private $username = 'root';
        private $connection;

        // DB Connect
        public function connect() {
            $this->connection = null;

            try {
                $this->connection = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch(PDOException $error) {
                $error_message = 'Database Error: ';
                $error_message .= $error->getMessage();
                echo $error_message;
                exit('Unable to connect to the database');
            }

            return $this->connection;
        }
    }
?>