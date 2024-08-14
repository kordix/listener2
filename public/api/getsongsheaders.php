<?php

require('db.php');

$sth = $dbh->prepare("SELECT * FROM songs");

$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows);
