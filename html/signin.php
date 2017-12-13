<?php
	session_start();

	include_once 'connectionBDD.php';
	include_once 'utils.php';

	/* Si utilisateur pas auth => redirection */
	if (!isset($_SESSION['id'])) {
		header ('Location: index.php');
		exit();
	}

	/* Verification si user admin */
	$currentUser = getUser($_SESSION['id'],$bddcon);
	if ($currentUser['role'] != 1) {
		header ('Location: index.php');
		exit();
	}
	
	/* Si username et password fournit */
	if (isset($_POST['username'],$_POST['password'])){
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		$role = $_POST['role'];
		$activation = $_POST['activation'];
		
		/* Recherche user dans le bdd : Si rien trouvé alors création */
		$user = existingUser($username, $bddcon);
		if($user == '' && $username != '' && $password != ''){
			newUser($username, $password, $role, $activation, $bddcon);
			header('Location: usersManager.php');
		}	
	}
?>

<html>
	<head>
		<title>Sigin</title>
	</head>
	<body>
		<h1> Signin </h1>
		<form action="signin.php" method="post">
			Login : <input type="text" name="username" placeholder="Enter Username"/><br />
			Password : <input type="password" name="password"/><br />
			Role : <input type="number" name="role" placeholder="1:admin 0:user"/><br />
			Activation : <input type="number" name="activation" placeholder="1:active 0:disable"/><br />
			<input type="submit" name="sigin" value="signin">
		</form>
	</body>
</html>
