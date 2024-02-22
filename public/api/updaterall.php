<?php

echo 'DUPA';
// Jeśli nie ma żądania POST, możemy zakończyć skrypt
// if($_SERVER['REQUEST_METHOD'] != 'POST') return;

// Wymagane połączenie z bazą danych
require('../../db.php');

// Pobranie danych z JSON-a
$dane = json_decode(file_get_contents('php://input'));

$params = [];

// Iteracja przez tablicę obiektów
foreach ($dane as $element) {
    $allowed = ['song','tekst','start','duration'];

    $setStr = "";
    foreach ($allowed as $key) {
        if (property_exists($element, $key) && $key != "id" && $key != "token") {
            $setStr .= "`" . str_replace("`", "``", $key) . "` = :" . $key . ",";
            $params[$key] = $element->$key;
        }
    }
    $setStr = rtrim($setStr, ",");

    echo $element->start;

    // Zbudowanie zapytania SQL
    $query = "UPDATE songs_data SET $setStr WHERE id = :id";

    // Dodanie id do parametrów
    $params['id'] = $element->id;

    // echo $query;
    // Wykonanie zapytania
    $sth = $dbh->prepare($query)->execute($params);
}
