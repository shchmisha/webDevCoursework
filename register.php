<?php
require_once 'connection.php';

session_start();

$_SESSION;

if(isset($_SESSION['user'])){
	header("location: welcome.php");
}

if(isset($_REQUEST['register_btn'])){
	// echo "<pre>";
	// 	print_r($_REQUEST);
	// echo "</pre>";
	$username=filter_var($_REQUEST['UserName'], FILTER_UNSAFE_RAW);
	$firstname=filter_var($_REQUEST['FirstName'], FILTER_UNSAFE_RAW);
	$lastname=filter_var($_REQUEST['LastName'], FILTER_UNSAFE_RAW);
	$password=strip_tags($_REQUEST['password']);
	$repeat_password=strip_tags($_REQUEST['repeat_password']);
	$display = 0;
	if (isset($_REQUEST['display'])) {
		$display = 1;
	}

	if(empty($username)){
		$errorMsg[0][] = 'Username required';
	}
	if(empty($firstname)){
		$errorMsg[1][] = 'Firstname required';
	}
	if(empty($lastname)){
		$errorMsg[2][] = 'Secondname required';
	}
	if(empty($password)){
		$errorMsg[3][] = 'Password required';
	}
	if(strlen($password)<6){
		$errorMsg[3][] = 'Password less than 6 characters';
	}
	if($password != $repeat_password){
		$errorMsg[3][] = 'Passwords dont match';
	}
	if(empty($errorMsg)){
		try{
			// $create_stmt = $db->prepare("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT NOT NULL,name varchar(50) NOT NULL, email varchar(100) NOT NULL, password char(100) NOT NULL, created DATATIME NOT NULL)");
			// $create_stmt->execute();
			$select_stmt = $db->prepare("SELECT UserName FROM users WHERE UserName=:username");
			$select_stmt->execute([':username' => $username]);
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);

			if(isset($row['UserName']) == $username){
				 $errorMsg[0][] = "Username already exists";
			} else{
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$sql = "INSERT INTO users (UserName,FirstName,LastName,Password,Display) VALUES (:username,:firstname,:lastname,:password,:display)";
				$insert_stmt = $db->prepare($sql);
			
				if (
					$insert_stmt->execute(
						[
							':username' => $username, 
							':firstname' => $firstname, 
							':lastname' => $lastname, 
							':password' => $hashed_password,
							':display' => $display
						]
						)
				) {
					header("location: index.php");
				}
				
			}
		}catch(PDOException $e){
			$pdoError = $e->getMessage();
		}
	}
}



?>

<html lang="en">

<ul>
	<li><a href="tetris.php">Play Tetris</a></li>
	<li><a href="leaderboard.php">Leaderboard</a></li>
	</ul>
	<br>
	<br>
	<br>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register</title>
	<link rel="stylesheet" href=styles.css>
</head>
<body>
	
	<div class="regform">
		
		<form action="register.php" method="post">
			
			<div>

				<?php

					if(isset($errorMsg[0])) {
						foreach($errorMsg[0] as $nameErrors) {
							echo "<p class='small text-danger'>".$nameErrors."</p>";
						}
					}
				?>

				<label for="UserName">UserName</label>
				<input type="text" name="UserName">
			</div>
			<div>

				<?php

					if(isset($errorMsg[0])) {
						foreach($errorMsg[0] as $nameErrors) {
							echo "<p class='small text-danger'>".$nameErrors."</p>";
						}
					}
				?>

				<label for="FirstName">FirstName</label>
				<input type="text" name="FirstName">
			</div>
			<div>

				<?php

					if(isset($errorMsg[0])) {
						foreach($errorMsg[0] as $nameErrors) {
							echo "<p class='small text-danger'>".$nameErrors."</p>";
						}
					}
				?>

				<label for="LastName">LastName</label>
				<input type="text" name="LastName">
			</div>
			<div>

			<?php

				if(isset($errorMsg[2])) {
					foreach($errorMsg[2] as $passwordErrors) {
						echo "<p class='small text-danger'>".$passwordErrors."</p>";
					}
				}
			?>

				<label for="password">Password</label>
				<input type="password" name="password" placeholder="">
				
			</div>
			<div>

			<?php

				if(isset($errorMsg[2])) {
					foreach($errorMsg[2] as $passwordErrors) {
						echo "<p class='small text-danger'>".$passwordErrors."</p>";
					}
				}
			?>

				<label for="repeat_password">Repeat Password</label>
				<input type="password" name="repeat_password" placeholder="">
				
			</div>

			<label for="display">Display scores?</label>
			<input type="checkbox" name="display">
  			
			<button type="submit" name="register_btn" >Register Account</button>
		</form>
		Already Have an Account? <a href="index.php">Login Instead</a>
	</div>
</body>
</html>