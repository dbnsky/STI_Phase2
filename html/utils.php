
<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : utilss.php
  Auteurs     : Emmanuel Schmid, Iando Rafidimalala
  Date        : 04.12.2017
  Description : Fonctions permettant de manipuler la base de donnée et 
				de validation de la politique lié au mot de passe
  Utile: http://php.net/manual/fr/mysqli-stmt.bind-param.php
  ---------------------------------------------------------------------------
 */
 

	function inputCleanXSS($inputToClean){
		return htmlentities($inputToClean, ENT_COMPAT | ENT_HTML401, 'UTF-8');
	}
	
	/* Validation du password selon la politique
	 * 
	 *  */
	function passwordPolicy($password){
	
		if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{6,15}$#',$password)){
			return true;
    		} else {
			return false;
		}	
	}
	
	/* Création msg */
	function newMessage( $expediteurId,$desinataireUserId, $sujet, $message, $bddcon){
		$stmt = "INSERT INTO messages (expediteur, destinataire, sujet, message) VALUES (:expediteurId, :desinataireUserId, :sujet, :message);";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':expediteurId'      => htmlspecialchars($expediteurId),
							':desinataireUserId' => htmlspecialchars($desinataireUserId), 
							':sujet'             => htmlspecialchars($sujet),
							':message'           => htmlspecialchars($message));
	}
			
	/* Retourne un message en fonction d'un id */
	function getMessage($id, $bddcon)
	{
		/*
		$stmt = "SELECT * FROM messages WHERE id = $id;";
		$result = $bddcon->query($stmt);
		$message = $result->fetchAll();		
		return $message[0];
		*/
		$stmt = "SELECT * FROM messages WHERE id = :id";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':id' => $id));

		return $res;
    		
	}
	
	/* Retourne tous les messages d'un utilisateur */
	function getMessages($idUser, $bddcon)
	{		
		$stmt = "SELECT * FROM messages WHERE destinataire = :idUser ORDER BY dateEnvoi DESC;";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':id' => $idUser));

		return $res;
	}


	/* Supprimer un message en fonction de id du message*/
	function deleteMessage($id, $bddcon)
	{
		$stmt = "DELETE FROM messages WHERE id = :id;";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':id' => $id));

	}


	/* Création user */
	function newUser($username, $password, $role, $activation, $bddcon){
		$stmt = "INSERT INTO utilisateurs (nomUtilisateur, motDePasse, role, activation) VALUES (:username, :password, :role, :activation);";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':username' => htmlspecialchars($username),
							':password' => $password, 
							':role' => htmlspecialchars($role),
							':activation' => htmlspecialchars($activation));
	}
		

	/* Retourne tout les utilisateurs */
	function getAllUsers($bddcon){
		$stmt = "SELECT * FROM utilisateurs";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		
		$res->execute(array());
		return $res;
	}

	/* Verfie l'existence d'un user */
	function existingUser($username, $bddcon){
		$stmt = "SELECT * FROM utilisateurs WHERE nomUtilisateur= :username";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':username' => $username));
		
		
		$result = $res->fetchAll();
		
		if (!empty($result[0])) {
			return $result[0];
		} else {
			return false;
		}		
	}


	/* Retourner le user en fonction de l'id */
	function getUser($id, $bddcon) {
		$stmt = "SELECT * FROM utilisateurs WHERE id = :id";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':id' => $id));
		
		return $res;
	}

	/* update du active session */
	function inverseActive($idUser, $active, $bddcon)
	{
		$stmt = "UPDATE utilisateurs SET activation = :active WHERE id = :idUser;";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':active' => $active, ':idUser' => $idUser));
	}


	/* update du role*/
	function inverseRole($idUser, $role, $bddcon)
	{
		$stmt = "UPDATE utilisateurs SET role = :role WHERE id = :idUser;";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':role' => $role, ':idUser' => $idUser));

	}
	
	/* update password dont le nouveau mot de pass est hashé */
	function updatePassword($idUser, $newPwd, $bddcon)
	{
		$stmt = "UPDATE utilisateurs SET motDePasse=:newPwd WHERE id = :idUser;";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));		
		$res->execute(array(':newPwd' => $newPwd, ':idUser' => $idUser));		
	}

	/* Supprimer un utilisateur  */
	function deleteUser($idUser, $bddcon)
	{
		$stmt = "DELETE FROM utilisateurs WHERE id = :idUser;";
		$res = $bddcon->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$res->execute(array(':idUser' => $idUser));
	}

?>
