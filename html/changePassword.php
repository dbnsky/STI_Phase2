<?php
	
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	
	session_start();

	/* Si user pas auth */
	if (!isset($_SESSION['id'])) {
		header ('Location: index.php');
		exit();
	}
	
	/* Vérification si paramètre id présent */
	if (!isset($_GET['id'])){
		header ('Location: usersManager.php');
		exit();
	}
	
	$idUserToUpdate = $_GET['id'];
	$currentUser = getUser($_SESSION['id'], $bddcon);
	/* Verification admin si changement sur autre compte user => redirection */
	if ($currentUser['role'] != 1) {
		if($idUserToUpdate!= $currentUser['id']){
			header ('Location: index.php');
			exit();
		}
	}

	// Update du mdp dans la bdd si mdp fourni
	if (isset($_POST['password'])){
		$newPwd = $_POST['password'];
		updatePassword($idUserToUpdate, $newPwd, $bddcon);
		echo 'Mot de passe mise à jour !';	
	}
?>


<html>
	<head>
		<title> Changement mot de passe </title>
	</head>

	<body>
		<form action="changePassword.php?id=<?php echo $idUserToUpdate ?>" method="post">
			Nouveau mdp : <input type="password" name="password" value="">
			<input type="submit" name="changePassword" value="Changer">
		</from>
		<ul>
			<li>
			<a href="inbox.php"> Inbox </a>
			</li>
		</ul>
	</body>
</html>
