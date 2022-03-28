<?php
require_once 'connection.php';
session_start();

if(!isset($_SESSION['user'])){
	header("location: index.php");
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
// }

if (isset($_REQUEST['score'])) {
    $select_stmt = $db->prepare("SELECT * FROM users WHERE email=:email LIMIT 1");
    $select_stmt->execute([
        ':email'=>$_SESSION['user']['email']
    ]);
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    if($row['highscore'] < $_REQUEST['score']) {
        $update_stmt = $db->prepare("UPDATE users SET highscore=:highscore WHERE email=:email");
        $update_stmt->execute([
            ':highscore' => $_REQUEST['score'],
            ':email' => $_SESSION['user']['email']
        ]);
    } else {
        echo "<p>score lower than highscore, score:".$_REQUEST['score']."</p>";
    }
}

?>

<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
    </head>
    <body>
        
        
        <div>
            <button id="start-button">Start/Pause</button>
            <canvas
            id = "game-canvas"
            width="300" 
            height="600"
            ></canvas>
            <h2 id="scoreboard">Score: 0</h2>
            <a href="logout.php">logout</a>
            <a href="leaderboard.php">leaderboard</a>
        </div>
        
        <!-- <form action="routes.php" method="GET">
            <input type="text" name="Name">
            <input type="submit" value="send">
        </form> -->
        <!-- <script>
            var src="routes.php?id=5";
            window.location.href=src;
        </script> -->
        <script type="text/javascript" src="constants.js"></script>
        <script type="text/javascript" src="piece.js"></script>
        <script type="text/javascript" src="gamemodel.js"></script>
        <script type="text/javascript" src="tetris.js"></script>
    </body>
</html>