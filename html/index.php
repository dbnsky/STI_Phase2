<?php
	session_start();
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	require 'reCaptcha.php';
	include_once 'sessionManager.php';
	require 'password.php';

	if(isSessionAuth()){
		header('Location: inbox.php');
		exit();
	}

	generateHashTokenForm('index.php');

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$urlPage = "http://localhost/inbox.php";
		$checkUrl = $_SERVER['HTTP_REFERER'];
		/* Validitée du token et verif de l'origine */
		if($urlPage != $checkUrl && $_SESSION['CSRFToken'] != $_POST['tokenForm']){
			header ('Location: index.php');
			exit();
		}
		//Envoie du formulaire de login complet (username, password, capcha)

		if (isset($_POST['username'], $_POST['password']) && $decode['success'] == true){
		
			//Clear input to avoid XSS attack
			$username = inputCleanXSS($_POST['username']);
			$password = inputCleanXSS($_POST['password']);
			if(empty($password) && empty($username)){
				header ('Location: index.php');
				exit();
			}
			// hash du mdp
			$hash = password_hash($password,PASSWORD_BCRYPT);
			// Recherche user dans le bdd
			$user = existingUser($username, $bddcon);
			// User found et actif

			if(!empty($user)) {
				if($user['activation'] == 1 ){
					// Control du mdp et création de la session 
					if(password_verify($password, $user['motDePasse'])){
						$_SESSION['id'] = $user['id'];
						initSessionLife();
						header('Location: inbox.php');
					} else {
							echo $hash;
						echo "Bad Username or Password !";
					}
				} else {
				 	echo "This account has been disable !";
				}
			} else {
				echo "Bad Username or Password";
			}
		} else {
			echo "Veuillez remplire tout les champs !";
		}
	}
?>

<html>
	<head>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<title> Login </title>
	</head>
	<body>
		<h1> Page d'authentification </h1>
		<form action="index.php" method="post">
		<strong>Nom d'utilisateur:</strong> <input type="text" name="username">
		<strong>Mot de passe : </strong> <input type="password" name="password" ><br />
		<input type="hidden" name="tokenForm" value="<?php echo $_SESSION['CSRFToken']; ?>" />
		<div class="g-recaptcha" data-sitekey="6LcGBUAUAAAAAEBVQDoQSywGjutLlzVL7xNyYCVe"></div>
		<input type="submit" name="inscription" value="Login">
		</form>
		
	</body>
</html>
