<?php
	session_start();
	
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	include_once 'sessionManager.php';
	require 'authentification.php';
	
	/* Répondre à un message */
	if(isset($_GET['idMessage'])){
		/* Clean input */
		$idMessage = inputCleanXSS($_GET['idMessage']);
		/* Contrôle : L'id est un entier */
		if(empty($idMessage) || intval($idMessage)){
			$message = getMessage($idMessage, $bddcon);
			/* Contrôle : Le destinataire du message == nouvel expéditeur sinon impossible */
			if($message['destinataire'] != $_SESSION['id']){
				header ('Location: inbox.php');
				exit();
			} else {
				$destinataireMessage = getUser($message['destinataire'],$bddcon);
				$sujetMessage = $message['sujet'];
			}
		} else {
			header ('Location: inbox.php');
			exit();
		}
	}

	/* Protection formulaire*/
	generateHashTokenForm('writeMessage.php');
	
	/* Envoie formulaire */
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		/* Contrôle : Présance des champs du formulaire */
		if (isset($_POST['sujet'], $_POST['destinataire'], $_POST['message']))
		{
			$urlPage = "http://localhost/writeMessage.php";
			$checkUrl = substr($_SERVER['HTTP_REFERER'], 0,strlen($urlPage));
			/* Contrôle : Token et origine */
			if ($urlPage == $checkUrl && $_SESSION['CSRFToken'] == $_POST['tokenForm'])
			{
				/* Clear input to avoid XSS attack */
				$sujet = inputCleanXSS($_POST['sujet']);
				$destinataire = inputCleanXSS($_POST['destinataire']);
				$message = inputCleanXSS($_POST['message']);
				
				$expediteurId = $_SESSION['id'];
				$desinataireUser = existingUser($destinataire, $bddcon);
				// Contrôle : destinataire trouvé
				if (empty($desinataireUser)){
					echo 'Destinataire pas trouvé !';			
				} else {
					/* Création d'un msg */		
					newMessage($expediteurId,$desinataireUser['id'], $sujet, $message, $bddcon);
					header ('Location: inbox.php');
					exit();
				}
		
			} else {
				echo "Request not Authorized";
			}
		} else {
			echo "Veuillez remplire tout les champs";
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

		Destinataire : <input type="text" name="destinataire" value="
<?php		
		if(isset($destinataireMessage)){
			echo $destinataireMessage['nomUtilisateur'];
		}

?>" >

		Sujet : <input type="text" name="sujet" value ="
<?php
		if(isset($sujetMessage)){
			echo 'Re:'.$sujetMessage;
		}

 ?>" >
		Message : <textarea name="message"></textarea>
		<input type="submit" name="envoyer" value="Envoyer">
		<input type="hidden" name="tokenForm" value="<?php echo $_SESSION['CSRFToken']; ?>" />
		</form>
		<ul>
			<li>
			<a href="inbox.php"> Inbox </a>
			</li>
		</ul>
</body></html>
