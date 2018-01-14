<?php
	session_start();
	
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	include_once 'sessionManager.php';
	require 'authentification.php';
	require 'password.php';

	generateHashTokenForm('/changePassword.php');

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$urlPage = "http://localhost/changePassword.php";
		$checkUrl = $_SERVER['HTTP_REFERER'];
		/* Verif : origine + token formulaire */
		if ($urlPage != $checkUrl && $_SESSION['CSRFToken'] != $_POST['tokenForm']){
			header ('Location: inbox.php');
			exit();
		}

		$currentUser = getUser($_SESSION['id'], $bddcon);
		if(empty($currentUser)){
			header ('Location: inbox.php');
			exit();
		}
		// Update du mdp dans la bdd si mdp fourni
		/* Clear input to avoid XSS attack */
		$currentPassowrd = inputCleanXSS($_POST['oldPassword']);
		$newPassword = inputCleanXSS($_POST['newPassword']);
		$newPassword2 = inputCleanXSS($_POST['newPassword2']);

		if (!empty($newPassword) && !empty($newPassword2) && !empty($currentPassowrd)){		
			if(password_verify($currentPassowrd, $currentUser['motDePasse'])){
				if($newPassword == $newPassword2){
		
					// Appliquer politique MDP + pas le même mdp 
					if(passwordPolicy($newPassword) && $currentUser['motDePasse'] != $newPassword){
						$hashPassword = password_hash($newPassword,PASSWORD_BCRYPT);
						updatePassword($_SESSION['id'], $hashPassword, $bddcon);
						echo 'Mot de passe mise à jour !';
					} else {
						echo "Mot de passe non conforme à la politique";
					}

				} else {
					echo "Mot de passe non identique";
				}
			} else {
				echo "Mot de passe incorrect";
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
		<form action="changePassword.php" method="post">
			mdp : <input type="password" name="oldPassword" value="">
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
