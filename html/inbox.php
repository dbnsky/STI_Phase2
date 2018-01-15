<?php
	session_start();
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	include_once 'sessionManager.php';
	require 'authentification.php';	
	
	generateHashTokenForm('inbox.php');
	
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
?>

<html>
	<head>
		<title>Inbox</title>
	</head>

	<body>
		<h1> Inbox </h1>
		<?php
			/* Récupère la liste des messages du user auth */
			$messages = getMessages($_SESSION['id'], $bddcon);
			/* Affichages des messages si il y en a */

			if(!empty($messages))
			{
			/* Création de l'en-tête du tableau */
				echo '<table BORDER="4" style="width:100%">';
				echo '<strong> Liste des messages </strong>';
				echo '<tr>';
				echo '<th> Expéditeur </th>';
				echo '<th> Sujet </th>';
				echo '<th> Date </th>';
				echo '<th> Ouvrir </th>';
				echo '<th> Répondre </th>';
				echo '<th> Supprimer </th>';
				echo '</tr>';
				/* Itération sur les messages pour remplir le tableau */
				foreach($messages as $msg)
				{
					$idExpediteur = $msg['expediteur'];
					$expediteurUser = getUser($idExpediteur, $bddcon);
					
					$date = $msg['dateEnvoi'];
					$sujet = $msg['sujet'];
					echo '<tr>';
					echo '<td>' . $expediteurUser['nomUtilisateur'] . '</td>';
					echo '<td>' . $sujet . '</td>';
					echo '<td>' . $date . '</td>';
					echo '<td> <a href="consultMessage.php?idMessage=', $msg['id'],'"> Lire </a> </td>';
					echo '<td> <a href="writeMessage.php?idMessage=',$msg['id'],'"> Répondre </a> </td>';
					echo '<td><form action="inbox.php" method="post">';
					echo '<input type="submit" value="Supprimer" onclick="return confirm(\'Confirmation?\');">';
					echo '<input type="hidden" name="idMsgDel" value=',$msg['id'],'>';
					echo '<input type="hidden" name="tokenForm" value=',$_SESSION['CSRFToken'].'/>';
					echo '</form></td>';
					echo '</tr>';
				}
			echo '</table>';
			}	
		?>
		<ul>
			<li>
				<a href="writeMessage.php"> Nouveau message</a>
			</li>
		<?php
		/* Menu en fonction du role */
			$currentUser = getUser($_SESSION['id'],$bddcon);
			if ($currentUser['role'] == 1) {
				echo '<li>';
				echo '<a href="usersManager.php"> List membre </a>';
				echo '</li>';
			}
			echo '<li>';
			echo '<a href="changePassword.php">Changer mot de passe </a>';
			echo '</li>';
			echo '<li>';
			echo '<a href="logout.php">Déconnexion</a>';
			echo '</li>';
			echo '</ul>';
?>
	</body>
</html>
