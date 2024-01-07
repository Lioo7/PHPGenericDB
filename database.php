<?php
include 'config.php';

class Database
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;

    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }

    private function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    
    public function createExampleTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS example_table (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255),
            age INT
        )";
        $this->query($sql);
    }


    public function query($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            die("Query preparation failed: " . $this->conn->error);
        }
    
        if (!empty($params)) {
            $types = "";
            $values = [];
    
            foreach ($params as $param) {
                $types .= $param['type'];
                $values[] = $param['value'];
            }
    
            $stmt->bind_param($types, ...$values);
        }
    
        $stmt->execute();
    
        if ($stmt->error) {
            die("Query execution failed: " . $stmt->error);
        }
    
        // Check if it's a SELECT query before trying to fetch results
        if (stripos($sql, 'SELECT') === 0) {
            $result = $stmt->get_result();
    
            if (!$result) {
                die("Result retrieval failed: " . $stmt->error);
            }
    
            return $result;
        } else {
            // Return the number of affected rows for non-SELECT queries
            return $stmt->affected_rows;
        }
    }     
    

    public function fetch($result)
    {
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function close()
    {
        $this->conn->close();
    }
}
?>
