<?php
require('dbinfo.php');
class database {
    public $connection;

    public function __construct() {
        $db_info = returnInfo('local');

        $conn = "mysql:host=" . $db_info['servername'] . ";dbname=" . $db_info['dbname'] . ";charset=" . $db_info['charset'];

        // try connectng
        try {
            $this->connection = new PDO($conn, $db_info['username'], $db_info['password']);
        } catch (PDOException $e) {
            exit('Connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function process($input_data, $sql) {
        $stmt = $this->connection->prepare($sql);
        if(!$stmt) {
            $this->error(0);
        }

        foreach($input_data as $key => $data) {
            $stmt->bindParam(':' . $key, $data['data']);
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->error(1, $e->getMessage());
        }
        return true;
    }

    public function fetch($input_data, $sql, $single = false) {
        $stmt = $this->connection->prepare($sql);
        if(!$stmt) {
            $this->error(0);
        }

        foreach($input_data as $key => $data) {
            $stmt->bindParam(':' . $key, $data['data']);
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->error(1, $e->getMessage());
        }

        if($single) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function fetchFromArray($input_data, $sql, $single = false) {
        $stmt = $this->connection->prepare($sql);
        if(!$stmt) {
            $this->error(0);
        }

        try {
            $stmt->execute($input_data);
        } catch (PDOException $e) {
            $this->error(1, $e->getMessage());
        }

        if($single) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function exists($input_data, $sql) {
        $stmt = $this->connection->prepare($sql);
        if(!$stmt) {
            $this->error(0);
        }

        foreach($input_data as $key => $data) {
            $stmt->bindParam(':' . $key, $data['data']);
        }

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            $this->error(1, $e->getMessage());
        }

        if($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function error($id, $info = null) {
        if($id == 0) {
            echo 'There is an error with database query, this will be fixed as soon as possible. <a href="/index.php">Return home</a>';
            exit();
        } else if($id == 1) {
            echo 'There is an error with the database, this will be fixed as soon as possible. <a href="/index.php">Return home</a>';
            exit();
        }
    }
}
