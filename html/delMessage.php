
<?php
	
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	session_start();
	/* Si utilisateur pas auth => Redirection */
	if (!isset($_SESSION['id'])) {
		header ('Location: index.php');
		exit();
	}
	
	/* Vérification si paramètre id présent */
	if (!isset($_GET['id'])){
		header ('Location: inbox.php');
		exit();
	}
	
	deleteMessage($_GET['id'], $bddcon);
	
	header('Location: ./inbox.php');
?>
