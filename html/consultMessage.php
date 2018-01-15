<?php
	session_start();
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	include_once 'sessionManager.php';
	require 'authentification.php';


	/* Envoie formulaire */
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$urlPage = "http://localhost/inbox.php";
		$checkUrl = $_SERVER['HTTP_REFERER'];
		/* Validitée du token et verif de l'origine */
		if($urlPage != $checkUrl && $_SESSION['CSRFToken'] != $_POST['tokenForm']){
			header ('Location: index.php');
			exit();
		}
		if (isset($_POST['idMsgDel'])){
			$idMessageToDel = inputCleanXSS($_POST['idMsgDel']);
			if(!empty($idMessageToDel) && intval($idMessageToDel)){

				$messageToDel = getMessage($idMessageToDel,$bddcon);
				if(!empty($messageToDel) && $_SESSION['id'] == $messageToDel['destinataire']){
				// Suppression message
				deleteMessage($idMessageToDel, $bddcon);
				}
			}
		}
	}

	/* Verification des paramètres */
	if(!isset($_GET['idMessage'])){
		header ('Location: inbox.php');
		exit();
	}

	$idMessage = inputCleanXSS($_GET['idMessage']);
	/* L'id est un entier sinon => inbox */
	if(empty($idMessage) || !intval($idMessage)){
		header ('Location: inbox.php');
		exit();
	}

	$message = getMessage($idMessage, $bddcon);
	
	/* Verification : aucun msg ou consult bien un msg qui lui est destiné */
	if(empty($message) || $message['destinataire'] != $_SESSION['id']){
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
	echo '<li><a href="writeMessage.php?idMessage=', $message['id'], '"> Répondre </a></li>';
	echo '<li><form action="inbox.php" method="post">';
	echo '<input type="submit" value="Supprimer" onclick="return confirm(\'Confirmation?\');">';
	echo '<input type="hidden" name="idMsgDel" value=',$msg['id'],'>';
	echo '<input type="hidden" name="tokenForm" value=',$_SESSION['CSRFToken'].'/>';
	echo '</form></li>';
	echo '<li><a href="inbox.php"> Inbox </a></li></ul></body></html>'
?>
			
