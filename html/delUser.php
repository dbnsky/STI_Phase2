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

	/* Vérification si paramètre id présent */
	if (!isset($_GET['id'])){
		header ('Location: inbox.php');
		exit();
	}
	
	/* Suppression user et redirection */
	deleteUser($_GET['id'], $bddcon);
	header('Location: usersManager.php');
	
?>
