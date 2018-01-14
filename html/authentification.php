<?php	
	/* Si utilisateur pas auth => redirection */
	if(!isSessionAuth()){
		header ('Location: index.php');
		exit();
	}

?>
