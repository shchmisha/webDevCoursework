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
	$name=filter_var($_REQUEST['name'], FILTER_UNSAFE_RAW);
	$email=filter_var(strtolower($_REQUEST['email']), FILTER_SANITIZE_EMAIL);
	$password=strip_tags($_REQUEST['password']);
	$repeat_password=strip_tags($_REQUEST['repeat_password']);

	if(empty($name)){
		$errorMsg[0][] = 'Name required';
	}
	if(empty($email)){
		$errorMsg[1][] = 'Email required';
	}
	if(empty($password)){
		$errorMsg[2][] = 'Password required';
	}
	if(strlen($password)<6){
		$errorMsg[2][] = 'Password less than 6 characters';
	}
	if($password != $repeat_password){
		$errorMsg[2][] = 'Passwords dont match';
	}
	if(empty($errorMsg)){
		try{
			// $create_stmt = $db->prepare("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT NOT NULL,name varchar(50) NOT NULL, email varchar(100) NOT NULL, password char(100) NOT NULL, created DATATIME NOT NULL)");
			// $create_stmt->execute();
			$select_stmt = $db->prepare("SELECT name,email FROM users WHERE email=:email");
			$select_stmt->execute([':email' => $email]);
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);

			if(isset($row['email']) == $email){
				 $errorMsg[1][] = "Email already exists";
			} else{
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$created = new DateTime();
				$created = $created->format('Y-m-d H:i:s');
				
				$sql = "INSERT INTO users (name,email,password,created,highscore) VALUES (:name,:email,:password,:created,:highscore)";
				$insert_stmt = $db->prepare($sql);
			
				if (
					$insert_stmt->execute(
						[
							':name' => $name, 
							':email' => $email, 
							':password' => $hashed_password, 
							':created' => $created,
							':highscore' => 0
						]
						)
				) {
					header("location: index.php?msg=".urlencode('Click the verification email'));
				}
				
			}
		}catch(PDOException $e){
			$pdoError = $e->getMessage();
		}
	}
}



?>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register</title>
</head>
<body>
	<div>
		
		<form action="register.php" method="post">
			<div>

				<?php

					if(isset($errorMsg[0])) {
						foreach($errorMsg[0] as $nameErrors) {
							echo "<p class='small text-danger'>".$nameErrors."</p>";
						}
					}
				?>

				<label for="name">Name</label>
				<input type="text" name="name" placeholder="Jane Doe">
			</div>
			<div>

			<?php

				if(isset($errorMsg[1])) {
					foreach($errorMsg[1] as $emailErrors) {
						echo "<p class='small text-danger'>".$emailErrors."</p>";
					}
				}
			?>

				<label for="email">Email address</label>
				<input type="email" name="email" placeholder="jane@doe.com">
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
			<button type="submit" name="register_btn" >Register Account</button>
		</form>
		Already Have an Account? <a href="index.php">Login Instead</a>
	</div>
</body>
</html>