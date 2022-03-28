<?php
require_once 'connection.php';

$select_stmt = $db->prepare("SELECT name,highscore FROM users");
$select_stmt->execute();
$rows = $select_stmt->fetchAll();


?>


<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
    </head>
    <body>
        <?php
            foreach($rows as $row) {
                echo "<p>".$row['name']."-".$row['highscore']."</p>";
            }
        ?>
    </body>
</html>