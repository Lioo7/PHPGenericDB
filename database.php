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

    public function executeQuery($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Query preparation failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $this->bindParams($stmt, $params);
        }

        $stmt->execute();

        if ($stmt->error) {
            die("Query execution failed: " . $stmt->error);
        }

        return $stmt;
    }

    public function select($sql, $params = [])
    {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->get_result();
    }

    public function insert($sql, $params = [])
    {
        $stmt = $this->executeQuery($sql, $params);
        return $this->conn->insert_id;
    }

    public function update($sql, $params = [])
    {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->affected_rows;
    }

    public function delete($sql, $params = [])
    {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->affected_rows;
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

    private function bindParams($stmt, $params)
    {
        $types = "";
        $values = [];

        foreach ($params as $param) {
            $types .= $param['type'];
            $values[] = $param['value'];
        }

        $stmt->bind_param($types, ...$values);
    }
}

?>
