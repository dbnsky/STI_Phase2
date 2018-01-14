<?php
	session_start();
	
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	include_once 'sessionManager.php';
	require 'authentification.php';
	require 'password.php';

	/* Verification si user admin */
	$currentUser = getUser($_SESSION['id'],$bddcon);
	if ($currentUser['role'] != 1) {
		header ('Location: index.php');
		exit();
	}
	/* Vérification si paramètre id présent et que ce soit un entier */
	if (!isset($_GET['id'])){
		header ('Location: usersManager.php');
		exit();
	}

	$idUserToUpdate = inputCleanXSS($_GET['id']);
	/* L'id est un entier sinon => usersManager */
	if(!intval($idUserToUpdate)){
		header ('Location: usersManager.php');
		exit();
	}
	$userToUpdate = getUser($idUserToUpdate, $bddcon);
	$currentUser = getUser($_SESSION['id'], $bddcon);
	if(empty($currentUser) && empty($userToUpdate)){
		header ('Location: usersManager.php');
		exit();
	}


	generateHashTokenForm('/adminChangePassword.php');

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$urlPage = "http://localhost/adminChangePassword.php";
		$checkUrl = $_SERVER['HTTP_REFERER'];
		/* Verif : origine + token formulaire */
		if ($urlPage != $checkUrl && $_SESSION['CSRFToken'] != $_POST['tokenForm']){
			header ('Location: inbox.php');
			exit();
		}

		// Update du mdp dans la bdd si mdp fourni
		/* Clear input to avoid XSS attack */
		$newPassword = inputCleanXSS($_POST['newPassword']);
		$newPassword2 = inputCleanXSS($_POST['newPassword2']);

		if (!empty($newPassword) && !empty($newPassword2)){
			if($newPassword == $newPassword2){
				// Appliquer politique MDP + pas le même mdp
				
				if(passwordPolicy($newPassword)){
					$hashPassword = password_hash($newPassword,PASSWORD_BCRYPT);
					updatePassword($userToUpdate['id'], $hashPassword, $bddcon);
					echo 'Mot de passe mise à jour !';
				} else {
					echo "Mot de passe non conforme à la politique";
				}

			} else {
				echo "Mot de passe non identique";
			}
		} else {
			echo "Remplire tout les champs";
		}
	}
?>


<html>
	<head>
		<title> Changement mot de passe </title>
	</head>

	<body>
		<form action="adminChangePassword.php?id=<?php echo $idUserToUpdate ?>" method="post">
			Nouveau mdp : <input type="password" name="newPassword" value="">
			Retaper mdp : <input type="password" name="newPassword2" value="">
			<input type="hidden" name="tokenForm" value="<?php echo $_SESSION['CSRFToken']?>" />
			<input type="submit" name="changePassword" value="Changer">
		</from>
		<ul>
			<li>
			<a href="inbox.php"> Inbox </a>
			</li>
		</ul>
	</body>
</html>
