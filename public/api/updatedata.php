<?php
//if($_SERVER['REQUEST_METHOD'] != 'POST') return;


require('db.php');



//replace
$dane = json_decode(file_get_contents('php://input'));

$params = [];

print_r($dane);


// $params['id'] = $dane->id;
$allowed = ['song','tekst','start','duration'];

$setStr = "";
foreach ($allowed as $key) {
    if (property_exists($dane, $key) && $key != "id" && $key != "token") {
        $setStr .= "`" . str_replace("`", "``", $key) . "` = :" . $key . ",";
        $params[$key] = $dane->$key;
    }
}
$setStr = rtrim($setStr, ",");

//echo $kwerenda;

$query = "UPDATE songs_data SET $setStr WHERE id = $dane->id";

$sth = $dbh->prepare($query)->execute($params);


?>



