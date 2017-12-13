
<?php
	/* Retourne un message en fonction d'un id */
	function getMessage($id, $bddcon)
	{
		$query = "SELECT * FROM messages WHERE id = $id ;";
		$result = $bddcon->query($query);
		$message = $result->fetchAll();
		return $message[0];
	}
	
	/* Retourne les messages d'un utilisateur */
	function getMessages($idUser, $bddcon)
	{		
		$query = "SELECT * FROM messages WHERE destinataire = $idUser ORDER BY dateEnvoi DESC;";
		$result = $bddcon->query($query);
		$messages = $result->fetchAll();
		return $messages;
	}

	/* Supprime un message  */
	function deleteMessage($idMessage, $bddcon)
	{
		$query = "DELETE FROM messages WHERE id = $idMessage;";
		$bddcon->query($query);
	}
	
	/* Supprime un utilisateur  */
	function deleteUser($idUser, $bddcon)
	{
		$query = "DELETE FROM utilisateurs WHERE id = $idUser;";
		$bddcon->query($query);
	}

	/* Retourne tout les utilisateurs */
	function getAllUsers($bddcon){
		$query = "SELECT * FROM utilisateurs";
		$result = $bddcon->query($query);
		$users = $result->fetchAll();
		return $users;
	}

	/* update du role*/
	function inverseRole($idUser, $role, $bddcon)
	{
		$query = "UPDATE utilisateurs SET role = $role WHERE id = $idUser;";
		$bddcon->query($query);
	}

	/* update du active session */
	function inverseActive($idUser, $active, $bddcon)
	{
		$query = "UPDATE utilisateurs SET activation = $active WHERE id = $idUser;";
		$bddcon->query($query);
	}

	/* update password */
	function updatePassword($idUser, $newPwd, $bddcon)
	{
	$query = "UPDATE utilisateurs SET motDePasse='$newPwd' WHERE id = $idUser;";
	$bddcon->query($query);
	}

	/* Verfie l'existence d'un user */
	function existingUser($username, $bddcon){
		$query = "SELECT * FROM utilisateurs WHERE nomUtilisateur='$username'";
		$result = $bddcon->query($query);
		$user = $result->fetchAll();
		return $user[0];
	}

	/* Création user */
	function newUser($username, $password, $role, $activation, $bddcon){
		$query = "INSERT INTO utilisateurs (nomUtilisateur, motDePasse, role, activation) VALUES (' $username', '$password', $role, $activation);";
		$bddcon->query($query);
	}

	/* Création msg */
	function newMessage( $expediteurId,$desinataireUserId, $sujet, $message, $bddcon){
		$query = "INSERT INTO messages (dateEnvoi, expediteur, destinataire, sujet, message) VALUES ('" . date('Y-m-d H:i:s') . "'," . $expediteurId . ", " . $desinataireUserId . ", '" . $sujet . "', '" . $message . "');";
		$bddcon->query($query);
	}

	/* Retourne le user en fonction de l'id */
	function getUser($id, $bddcon) {
		$query = "SELECT * FROM utilisateurs WHERE id = $id";
		$result = $bddcon->query($query);
		$user = $result->fetchAll();
		return $user[0];
	}

?>
