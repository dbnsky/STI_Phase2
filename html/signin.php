<?php
	session_start();

	include_once 'connectionBDD.php';
	include_once 'utils.php';
	include_once 'sessionManager.php';
	require 'password.php';

	/* Verification si user admin */
	$currentUser = getUser($_SESSION['id'],$bddcon);
	if ($currentUser['role'] != 1) {
		header ('Location: index.php');
		exit();
	}

	generateHashTokenForm('signin.php');

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$urlPage = "http://localhost/signin.php";
		$checkUrl = $_SERVER['HTTP_REFERER'];
		/* Validitée du token et verif de l'origine */
		if($urlPage != $checkUrl && $_SESSION['CSRFToken'] != $_POST['tokenForm']){
			header ('Location: usersManager.php');
			exit();
		}

		// Si username et password fournit
		if (isset($_POST['username'],$_POST['password'])){
			//Clear input to avoid XSS attack
			$username = inputCleanXSS($_POST['username']);
			$password = inputCleanXSS($_POST['password']);

			if(empty($password) && empty($username)){
				header ('Location: index.php');
				exit();
			}

			if(passwordPolicy($password)){

				$passwordHash = password_hash($_POST['password'],PASSWORD_BCRYPT);

				if(isset($_POST['role'])){
					$role = 1;
				} else {
					$role = 0;
				}

				if(isset($_POST['activation'])){
					$activation = 1;
				} else {
					$activation = 0;
				}

				// Recherche user dans le bdd : Si rien trouvé alors création 
				$user = existingUser($username, $bddcon);
				if(empty($user)){
					newUser($username, $passwordHash, $role, $activation, $bddcon);
					header('Location: usersManager.php');
				}
			} else {
				echo "Password pas conforme à la politique de mdp";
			}
		} else {
			echo "Fournir au minimum un username et un password";
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
			Role : <input type='CheckBox' name='role' value='Admin'><br />
			Activation : <input type='CheckBox' name='activation' value='Active'><br />
			<input type="hidden" name="tokenForm" value="<?php echo $_SESSION['CSRFToken']; ?>" />
			<input type="submit" name="sigin" value="signin">
		</form>
	</body>
</html>
