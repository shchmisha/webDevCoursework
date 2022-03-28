<?php
require_once 'connection.php';

session_start();

if(isset($_SESSION['user'])) {
	header("location: tetris.php");
}

if(isset($_REQUEST['login_btn'])) {
	$email=filter_var(strtolower($_REQUEST['email']), FILTER_SANITIZE_EMAIL);
	$password=strip_tags($_REQUEST['password']);

	if(empty($email)) {
		$errorMsg[] = 'Email empty';
	}
	else if (empty($password)) {
		$errorMsg[] = 'Password empty';
	}
	else {
		try{
			$select_stmt = $db->prepare("SELECT * FROM users WHERE email=:email LIMIT 1");
			$select_stmt->execute([
				':email'=>$email
			]);
			$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
			if($select_stmt->rowCount()>0){
				if(password_verify($password, $row["password"])) {
					$_SESSION['user']['name'] = $row["name"];
					$_SESSION['user']['email'] = $row["email"];
					$_SESSION['user']['id'] = $row["id"];
					header("location: tetris.php");
				}
				else{
					$errorMsg[] = 'Wrong email or password';
				}
			}
			else{
				$errorMsg[] = 'Wrong email or password';
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
          <label for="email">Email address</label>
          <input type="email" name="email" placeholder="jane@doe.com">
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