<?php
	
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	
	session_start();

	/* Si utilisateur pas auth => redirection */
	if (!isset($_SESSION['id'])) {
		header ('Location: index.php');
		exit();
	}
	
	/* Verification des champs du formulaire */
	if (isset($_POST['sujet'], $_POST['destinataire'], $_POST['message']))
	{

		$sujet = $_POST['sujet'];
		$desinataireUser = existingUser($_POST['destinataire'], $bddcon);
		$expediteurId = $_SESSION['id'];
		$message = $_POST['message'];
		// Test si le destinataire est valide.
		if ($desinataireUser ==''){
			echo 'Destinataire pas trouvé !';			
		}
		else{
			/* Création d'un msg */		
			newMessage($expediteurId,$desinataireUser['id'], $sujet, $message, $bddcon);
			header ('Location: inbox.php');
			exit();
		}
		
	}
?>


<html>
	<head>
		<title> Ecrire message </title>
	</head>
	<body>
		<h1> Ecrire message </h1>
		<form action="writeMessage.php" method="post">

		Destinataire : <input type="text" name="destinataire" value="<?php
		if(isset($_GET['destinataire'])){
			$destinataire = getUser($_GET['destinataire'], $bddcon);
				echo $destinataire['nomUtilisateur'];
		}

?>" >

		Sujet : <input type="text" name="sujet" >
		Message : <textarea name="message"></textarea>
		<input type="submit" name="envoyer" value="Envoyer">
		</form>
		<ul>
			<li>
			<a href="inbox.php"> Inbox </a>
			</li>
		</ul>
</body></html>
