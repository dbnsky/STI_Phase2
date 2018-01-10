<?php
	/* Generate Token per page */



	function initSessionLife(){
		$_SESSION['start'] = time();
		$_SESSION['expiration'] = $_SESSION['start'] + 900;
	}

	function isSessionAuth($timeToLive = 900){
		
		/* Si utilisateur déjà auth */
		if (isset($_SESSION['id']) && !empty($_SESSION['id']) && isSessionValide()){
			//On modifie la date d'expiration puisque l'utilisateur est actif
			$_SESSION['expiration'] = time() + $timeToLive;
			return true;
		} else {
			return false;
		}
	}

	function isSessionValide(){
		if(time() >= $_SESSION['expiration']){
			return false;
		} else {
			return true;
		}
	}


	function generateHashTokenForm($filename){
		if(empty($_SESSION['CSRFToken'])){
			$_SESSION['CSRFToken'] = hash_hmac('sha256', $filename, bin2hex(openssl_random_pseudo_bytes(32)));
		}
	}

?>
