<?php
	include_once 'connectionBDD.php';
	include_once 'utils.php';

	session_start();

	/* Si utilisateur déjà auth */
	if ($_SESSION['id'] != ''){
		header('Location: inbox.php');
	}
	
	/* Si username et password sont fourni (formulaire) */
	if (isset($_POST['username'], $_POST['password']))
	{                 
		$username = $_POST['username'];
		$password = $_POST['password'];
		/* Recherche user dans le bdd */
		$user = existingUser($username, $bddcon);

		/* User found et actif */
		if($user != '' || $user['activation'] == 0 ){
			/* Control du mdp et création de la session */
			if($user['motDePasse'] == $password ){
				$_SESSION['id'] = $user['id'];
				header('Location: inbox.php');
			}
		}
	}
?>

<html>
	<head>
		<title> Login </title>
	</head>
	<body>
		<h1> Page d'authentification </h1>
		<form action="index.php" method="post">
		<strong>Nom d'utilisateur:</strong> <input type="text" name="username">
		<strong>Mot de passe : </strong> <input type="password" name="password" ><br />
		<input type="submit" name="inscription" value="Login">
	</body>
</html>
