<?php
require('db.php');
function insertuj()
{
    global $dbh;
    $tableName = 'songs_data';
    $insertdata = json_decode(file_get_contents('php://input'),true); // Assuming you're using some framework or library for handling requests, adjust accordingly

    // Extracting column names and values
    $columns = array_keys($insertdata[0]);
    $values = array_map(function ($obj) {
        return array_values($obj);
    }, $insertdata);

    $insertStatements = array_map(function ($vals) use ($tableName, $columns) {
        $escapedVals = array_map(function ($val) {
            if (is_string($val)) {
                return "'" . str_replace("'", "''", $val) . "'";
            } else {
                return $val;
            }
        }, $vals);
        return "INSERT INTO $tableName (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $escapedVals) . ");";
    }, $values);

    // Assuming you have a database connection established
    try {
        foreach ($insertStatements as $statement) {
            // Execute each statement
            $sth = $dbh->prepare($statement);
            $sth->execute();
            echo $statement . "<br>"; // Assuming you want to output each statement
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage(); // Handle error appropriately
    }
}


insertuj();