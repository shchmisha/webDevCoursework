<?php
require_once 'connection.php';


// if(!isset($_SESSION['user'])){
// 	header("location: index.php");
// }


$scores = array();

$select_users_stmt = $db->prepare("SELECT * FROM Users");
$select_users_stmt->execute();
$users = $select_users_stmt->fetchAll();

foreach($users as $user) {
    if($user['Display'] == 1) {
        $select_score_stmt = $db->prepare("SELECT * FROM Scores WHERE Username=:username ORDER BY Score DESC");
        $select_score_stmt->execute([
            ':username'=>$user['UserName']
        ]);
        $rows = $select_score_stmt->fetchAll();
        if ($rows[0]!=null) {
            $scores[] = $rows[0];
        }
    }
}

for ($i=0;$i<count($scores)-1;$i++) {
    for ($j=0;$j<count($scores)-$i-1;$j++) {
        if($scores[$j]['Score']<$scores[$j+1]['Score']) {
            $temp = $scores[$j];
            $scores[$j] = $scores[$j+1];
            $scores[$j+1] = $temp;
        }
    }
}

?>


<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <h1>Leaderboard</h1>

<ul>
<li><a href="index.php">Home</a></li>
  <li><a href="tetris.php">Play Tetris</a></li>
  <li><a href="logout.php">Logout</a></li>


</ul>

<br>
<br>
        <table border cellspacing=2 cellpadding=20>
        <tr><th>Username</th><th>Score</th>
        <?php
            foreach($scores as $score) {
                echo "<tr><td>".$score['Username']."</td><td>".$score['Score']."</td></tr>";
                
            }
            // echo $users
        ?>
        
    </body>
</html>