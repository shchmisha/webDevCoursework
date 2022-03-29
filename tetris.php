<?php
require_once 'connection.php';
session_start();

if(!isset($_SESSION['user'])){
	header("location: index.php");
}

if(isset($_POST)) {
    $select_stmt = $db->prepare("SELECT * FROM scores WHERE Username=:username ORDER BY Score DESC");
    $select_stmt->execute([
        ':username'=>$_SESSION['user']['username']
    ]);
    $rows = $select_stmt->fetchAll();

    foreach($rows as $row) {
        if ($row['Score'] != $_POST['score']) {
            $create_stmt = $db->prepare("INSERT INTO scores (Scoreid,Username,Score) VALUES (:scoreid,:username,:score)");
            $create_stmt->execute([
                ':scoreid'=>rand(0,100000000),
                ':username'=>$_SESSION['user']['username'],
                ':score'=>$_POST['score']
            ]);
        }
    }
    // deliver_response(200, "success", $_POST);
    echo $_POST['score'];
}

// function deliver_response($status, $status_message, $data) {
//  header("HTTP/1.1 $status $status_message");
//  $response['status'] = $status;
//  $response['status_message'] = $status_message;
//  $response['data'] = $data;

// $json_response = json_encode($response);
//  echo $json_response;
// }

// if (isset($_REQUEST['score'])) {

//     $select_stmt = $db->prepare("SELECT * FROM scores WHERE Username=:username ORDER BY Score DESC");
//     $select_stmt->execute([
//         ':username'=>$_SESSION['user']['username']
//     ]);
//     $rows = $select_stmt->fetchAll();

//     foreach($rows as $row) {
//         if ($row['Score'] != $_REQUEST['score']) {
//             $create_stmt = $db->prepare("INSERT INTO scores (Scoreid,Username,Score) VALUES (:scoreid,:username,:score)");
//             $create_stmt->execute([
//                 ':scoreid'=>rand(0,100000000),
//                 ':username'=>$_SESSION['user']['username'],
//                 ':score'=>$_REQUEST['score']
//             ]);
//         }
//     }

    
// }


?>

<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body class="canvas">
        <div>
        <div class="topnav">
            <a href="leaderboard.php">leaderboard</a>
            <a class="active" href="logout.php">logout</a>
        </div>

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

            <div>
                <canvas
                id = "game-canvas"
                width="300" 
                height="600"
                ></canvas>
            </div>
            
            <div>
                <button id="restart-button">Restart</button>
                <button id="start-button">Start/Pause</button>
            </div>
                
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