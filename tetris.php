<?php
require_once 'connection.php';
session_start();

if(!isset($_SESSION['user'])){
	header("location: index.php");
}

if(isset($_POST['score'])) {

    $create_stmt = $db->prepare("INSERT INTO scores (Scoreid,Username,Score) VALUES (:scoreid,:username,:score)");
    $create_stmt->execute([
        ':scoreid'=>rand(0,100000000),
        ':username'=>$_SESSION['user']['username'],
        ':score'=>$_POST['score']
    ]); 
}


?>

<html>

    <h1>Play Tetris</h1>

    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="leaderboard.php">leaderboard</a></li>
        <li><a href="logout.php">logout</a></li>
    </ul>

    <br>
    <br>

    <head>
        <meta charset="utf-8">
        <title>Play Tetris</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body class="canvas">
        <div>
        

        <?php
        if (isset($_REQUEST['score'])) {
            if($rows[0]['Score'] == $_REQUEST['score']) {
                echo "<p>new highscore!, score:".$_REQUEST['score']."</p>";
            } else {
                // echo "<p>score lower than highscore, score:".$_REQUEST['score']."</p>";
                echo "<p>score lower than highscore, score:".$_REQUEST['score']."</p>";
            }
        }
        ?>

        <div>
           

            <div>
                <h2 id="scoreboard">Score: 0</h2>
            </div>
            <div class="canvasContainer">
                <canvas
                    id = "game-canvas"
                    width="300" 
                    height="600"
                ></canvas>
                <img class='img' src="tetris-grid-bg.png" alt="" />
                
            </div>
            
            <div>
                <button id="restart-button">Restart</button>
                <button id="start-button">Start/Pause</button>
            </div>
                
        </div>
        
        <script type="text/javascript" src="constants.js"></script>
        <script type="text/javascript" src="piece.js"></script>
        <script type="text/javascript" src="gamemodel.js"></script>
        <script type="text/javascript" src="tetris.js"></script>
    </body>
</html>