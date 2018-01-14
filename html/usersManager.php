<?php
	session_start();
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	include_once 'sessionManager.php';
	require 'authentification.php';	

	/* Verification si user admin */
	$currentUser = getUser($_SESSION['id'],$bddcon);
	if ($currentUser['role'] != 1) {
		//header ('Location: index.php');
		exit();
	}

	generateHashTokenForm('inbox.php');
	
	/* Envoie formulaire */
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$urlPage = "http://localhost/usersManager.php";
		$checkUrl = $_SERVER['HTTP_REFERER'];
		/* Validitée du token et verif de l'origine */
		if($urlPage != $checkUrl && $_SESSION['CSRFToken'] != $_POST['tokenForm']){
			header ('Location: usersManager.php');
			exit();
		}
		if (isset($_POST['idUserDel'])){
			$idUserDel = inputCleanXSS($_POST['idUserDel']);
			if(!empty($idUserDel) && intval($idUserDel)){

				$userToDel = getUser($idUserDel,$bddcon);
				if(!empty($userToDel)){
				// Suppression message
					deleteUser($idUserDel, $bddcon);
				}
			}
		}

		if(isset($_POST['idActiveUser'])){
			$idUserActive = inputCleanXSS($_POST['idActiveUser']);
			/* L'id est un entier sinon => usersManager */
			if(!empty($idUserActive) || intval($idUserActive)){
				$userActive = getUser($idUserActive, $bddcon);
				if(!empty($userActive)){
					if($userActive['activation'] == 1)
						$active = 0;
					else
						$active = 1;

					inverseActive($idUserActive, $active, $bddcon);
				}
			}
		}
		if(isset($_POST['idRoleUser'])){
			$idUserRole = inputCleanXSS($_POST['idRoleUser']);
			/* L'id est un entier sinon => usersManager */
			if(!empty($idUserRole) || intval($idUserRole)){
				$userToUpdate = getUser($idUserRole, $bddcon);
				if(!empty($userToUpdate)){
					if($userToUpdate['role'] == 1)
						$role = 0;
					else
						$role = 1;
					inverseRole($idUserRole, $role, $bddcon);
				}
			}
	
		}

		
	}
?>

<html>
	<head>
		<title> Administratoin des utilisateurs </title>
	</head>
	<body>
		<?php	
			/* Rècupèration des uers */
			$users = getAllUsers($bddcon);

			// Liste tous les membres existant.
			if(!empty($users))
			{
				echo '<table BORDER="4" style ="width:100%">';
				echo '<strong> Liste des membres </strong>';
				echo '<tr>';
					echo '<th> Username </th>';
					echo '<th> Password </th>';
					echo '<th> Role </th>';
					echo '<th> Active </th>';
					echo '<th> Supprimer </th>';
				echo '</tr>';	
				foreach($users as $user)
				{
					$username = $user['nomUtilisateur'];
					$password = $user['motDePasse'];
					$role = $user['role'];
					$active = $user['activation'];
					echo '<tr>';
						echo '<td>'. $username .'</td>';
						echo '<td> <a href="adminChangePassword.php?id=', $user['id'], '"> changer </a> </td>';
					echo '<td><form action="usersManager.php" method="post">';
					echo '<input type="submit" value="Change" onclick="return confirm(\'Confirmation?\');">';
					echo '<input type="hidden" name="idRoleUser" value=',$user['id'],'>';
					echo '<input type="hidden" name="tokenForm" value=',$_SESSION['CSRFToken'].'/>';
					echo  $role;
					echo '</form></td>';

					echo '<td><form action="usersManager.php" method="post">';
					echo '<input type="submit" value="Change" onclick="return confirm(\'Confirmation?\');">';
					echo '<input type="hidden" name="idActiveUser" value=',$user['id'],'>';
					echo '<input type="hidden" name="tokenForm" value=',$_SESSION['CSRFToken'].'/>';
					echo  $active;
					echo '</form></td>';


					echo '<td><form action="usersManager.php" method="post">';
					echo '<input type="submit" value="Supprimer" onclick="return confirm(\'Confirmation?\');">';
					echo '<input type="hidden" name="idUserDel" value=',$user['id'],'>';
					echo '<input type="hidden" name="tokenForm" value=',$_SESSION['CSRFToken'].'/>';
					echo '</form></td>';

					echo '</tr>';
				}
			echo '</table>';
			}?>
			<ul>
				<li>
				<a href="signin.php">Nouveau utilisateur</a>
				</li>
				<li>
				<a href="logout.php">Déconnexion</a>
				</li>
				<li>
				<a href="inbox.php"> Inbox </a>
				</li>
			</ul>
		</body>
</html>
