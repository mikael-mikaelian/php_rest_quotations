<?php
    class Database {
        private $host = 'localhost';
        private $port = '5432';
        private $db_name = 'quotesdb';
        private $username = 'mikaelmikaelian';
        private $password = '';
        private $connection;

        // DB Connect
        public function connect() {
        $this->connection = null;
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
        try {

            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
            return $this->connection;
        }
    }
?>