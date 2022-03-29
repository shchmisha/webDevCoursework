<?php

$db_host = "localhost";
$db_user = "newuser";
$db_password = "";
$db_name = "tetris";

$db = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);

// try {
//     $db = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_password);
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// }
// catch(PDOEXCEPTION $e) {
//     echo $e->getMessage();
// }

