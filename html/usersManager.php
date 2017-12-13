<?php
	include_once 'connectionBDD.php';
	include_once 'utils.php';

	session_start();
	/* Verification si user auth */
	if (!isset($_SESSION['id'])) {
		header ('Location: index.php');
		exit();
	}
	
	/* Verification si user admin */
	$currentUser = getUser($_SESSION['id'],$bddcon);
	if ($currentUser['role'] != 1) {
		header ('Location: index.php');
		exit();
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
						echo '<td> <a href="changePassword.php?id=', $user['id'], '"> changer </a> </td>';
						echo '<td> <a href="updateRole.php?id=' ,$user['id'], '">' . $role . '</a> </td>';
						echo '<td> <a href="updateActive.php?id=' ,$user['id'], '">' . $active . '</a> </td>';
						echo '<td> <a href="delUser.php?id=', $user['id'], '">supprimer </a> </td>';
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
