<?php
include 'config.php';
include 'database.php';

// Example usage:
$database = new Database($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);

// Create example_table if it doesn't exist
$database->executeQuery("CREATE TABLE IF NOT EXISTS example_table (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    age INT
)");

// Example Insert Query:
$insertSql = "INSERT INTO example_table (name, age) VALUES (?, ?)";
$insertParams = [
    ['type' => 's', 'value' => 'Leo Messi'],
    ['type' => 'i', 'value' => 25],
];
$database->insert($insertSql, $insertParams);
echo "\nINSERT result:\n";
print_r($database->fetch($database->select("SELECT * FROM example_table WHERE name = 'Leo Messi'")));

// Example Select Query:
$sql = "SELECT * FROM example_table";
$result = $database->select($sql);

// Example fetching data:
$data = $database->fetch($result);
echo "SELECT result:\n";
print_r($data);

// Example Update Query:
$updateSql = "UPDATE example_table SET age = ? WHERE name = ?";
$updateParams = [
    ['type' => 'i', 'value' => 40],
    ['type' => 's', 'value' => 'Leo Messi'],
];
$database->update($updateSql, $updateParams);
echo "\nUPDATE result:\n";
print_r($database->fetch($database->select("SELECT * FROM example_table WHERE name = 'Leo Messi'")));

// Example Delete Query:
$deleteSql = "DELETE FROM example_table WHERE name = ?";
$deleteParams = [
    ['type' => 's', 'value' => 'Leo Messi'],
];
$database->delete($deleteSql, $deleteParams);
echo "\nDELETE result:\n";
print_r($database->fetch($database->select("SELECT * FROM example_table WHERE name = 'Leo Messi'")));

// Closing the database connection:
$database->close();
?>
