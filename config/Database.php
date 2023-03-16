<?php
    class Database {
        private $host;
        private $port;
        private $dbname;
        private $username;
        private $password;
        private $connection;

        public function __construct() {
            $this->host = getenv('HOST');
            $this->port = getenv('PORT');
            $this->dbname = getenv('DBNAME');
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
        }
        // DB Connect
        public function connect() {

        if ($this->connection){
          return $this->connection;
        } else {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
            return $this->connection;
        }
    }
    }
?>