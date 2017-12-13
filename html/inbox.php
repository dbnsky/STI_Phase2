<?php
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	session_start();

	/* Si utilisateur pas auth => redirection */
	if (!isset($_SESSION['id'])) {
		header ('Location: index.php');
		exit();
	}
?>

<html>
	<head>
		<title>Inbox</title>
	</head>

	<body>
		<h1> Inbox </h1>
		<?php
			/* L'id du user se trouve dans la session une fois auth */
			$idCurrentUser = $_SESSION['id'];
		
			$messages = getMessages($idCurrentUser, $bddcon);
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
					echo '<td> <a href="consultMessage.php?destinataire=', $msg['destinataire'],'&idMessage=', $msg['id'],'"> Lire </a> </td>';
					echo '<td> <a href="writeMessage.php?destinataire=',$msg['expediteur'],'"> Répondre </a> </td>';
					echo '<td> <a href="delMessage.php?id=', $msg['id'], '"> Supprimer </a> </td>';
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
			$currentUser = getUser($idCurrentUser,$bddcon);
			if ($currentUser['role'] == 1) {
				echo '<li>';
				echo '<a href="usersManager.php"> List membre </a>';
				echo '</li>';
			}
			echo '<li>';
			echo '<a href="changePassword.php?id=', $idCurrentUser, '">Changer mot de passe </a>';
			echo '</li>';
			echo '<li>';
			echo '<a href="logout.php">Déconnexion</a>';
			echo '</li>';
			echo '</ul>';
?>
	</body>
</html>
