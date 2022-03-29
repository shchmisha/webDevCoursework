<?php
require_once 'connection.php';

session_start();

if(isset($_SESSION['user'])) {
	header("location: tetris.php");
}

if(isset($_REQUEST['login_btn'])) {
	$username=filter_var($_REQUEST['UserName'], FILTER_UNSAFE_RAW);
	$password=strip_tags($_REQUEST['password']);

	if(empty($username)) {
		$errorMsg[] = 'Username empty';
	}
	else if (empty($password)) {
		$errorMsg[] = 'Password empty';
	}
	else {
		try{
			$select_stmt = $db->prepare("SELECT * FROM users WHERE UserName=:username LIMIT 1");
			$select_stmt->execute([
				':username'=>$username
			]);
			$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
			if($select_stmt->rowCount()>0){
				if(password_verify($password, $row["Password"])) {
					$_SESSION['user']['username'] = $row["UserName"];
					$_SESSION['user']['id'] = $row["Display"];
					// $_SESSION['user'][''] = $row["id"];
					header("location: tetris.php");
				}
				else{
					$errorMsg[] = 'Wrong username or password';
				}
			}
			else{
				$errorMsg[] = 'Wrong username or password';
			}
		}
		catch(PDOException $e) {
			echo $e -> getMessage();
		}

	}
}

?>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
</head>
<body>
	<div>
		<?php
			if(isset($_REQUEST['msg'])){
				echo "<p class='alert alert-warning'>".$_REQUEST['msg']."</p>";
			}
			if(isset($errorMsg)){
				foreach($errorMsg as $loginErr) {
					echo "<p class='alert alert-danger'>".$loginErr."</p>";
				} 
			}
		?>
		<form action="index.php" method="post">
		<div>
			<label for="UserName">UserName</label>
			<input type="text" name="UserName">
		</div>
        <div>
          <label for="password">Password</label>
          <input type="password" name="password" placeholder="">
        </div>
			<button type="submit" name="login_btn">Login</button>
		</form>
    No Account? <a href="register.php">Register Instead</a>
	</div>
</body>
</html>