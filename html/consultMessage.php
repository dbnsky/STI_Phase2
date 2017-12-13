<?php
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	
	session_start();

	/* Verification si user auth */
	if (!isset($_SESSION['id'])) {
		header ('Location: index.php');
		exit();
	}

	/* Verfication des paramètres */
	if(!isset($_GET['destinataire']) && !isset($_GET['idMessage'])){
		header ('Location: inbox.php');
		exit();
	}

	$idMessage = $_GET['idMessage'];
	$idDest = $_GET['destinataire'];
	
	$message = getMessage($idMessage, $bddcon);
	
	/* Verification que le user consult bien un msg qui lui est destiné */
	if($message['destinataire'] != $_SESSION['id']){
		header ('Location: inbox.php');
		exit();
	}

	$sujet = $message['sujet'];
	$sender = getUser($message['expediteur'], $bddcon);
	/* Affichage du messages */
	echo '<html><head><title> Message </title></head><body><h1> Consultation message </h1>';
	echo "Sujet  : " . $sujet . "<br/>"; 
	echo "Expéditeur  : " . $sender['nomUtilisateur'] . "<br/>";
	echo "Date : " . $message['dateEnvoi'] . "<br/>";
	echo "Message : " . $message['message'] . "";	
	echo'<ul>';
	echo '<li><a href="writeMessage.php?destinataire=', $message['destinataire'], '"> Répondre </a></li>';
	echo '<li><a href="delMessage.php?id=', $message['id'], '"> Supprimer </a></li>';
	echo '<li><a href="inbox.php"> Inbox </a></li></ul></body></html>'
?>
			
