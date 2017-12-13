<?php
	
	include_once 'connectionBDD.php';
	include_once 'utils.php';
	
	session_start();

	/* Si user pas auth => redirection */
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
		header ('Location: usersManager.php');
		exit();
	}
	
	$idUserToUpdate = $_GET['id'];
	$user = getUser($idUserToUpdate, $bddcon);

	if($user['activation'] == 1)
		$active = 0;
	else
		$active = 1;

	inverseActive($idUserToUpdate, $active, $bddcon);
	
	header('Location: usersManager.php');

	
?>
