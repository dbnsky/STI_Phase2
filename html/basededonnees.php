<?php
/*#########################################
Creation de la base de donnees et ouverture
de la connexion
#########################################*/


echo "test";


 
  // Set default timezone
  date_default_timezone_set('UTC');
 
  try {
 
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/var/www/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION); 
 
    /**************************************
    * Create tables                       *
    **************************************/
 
    // Create table messages
    $file_db->exec("

DROP TABLE IF EXISTS messages;
CREATE TABLE IF NOT EXISTS messages(

                    id INTEGER PRIMARY KEY,
                    expediteur INTEGER NOT NULL,
                    destinataire INTEGER NOT NULL,
                    sujet TEXT,
                    message TEXT,
                    dateEnvoi TEXT,  
                    FOREIGN KEY(expediteur) REFERENCES utilisateurs(id),
                    FOREIGN KEY(destinataire) REFERENCES utilisateurs(id)

                   )
                  ");

echo "la table des messages a ete cree\n";

 $file_db->exec("

DROP TABLE IF EXISTS utilisateurs;
CREATE TABLE IF NOT EXISTS utilisateurs(

                    id INTEGER PRIMARY KEY,
                    nomUtilisateur TEXT,
                    motDePasse TEXT, 
                    role TEXT,
                    activation INTEGER, 
                    CONSTRAINT nomUtilisateur_unique UNIQUE(nomUtilisateur)
                    )
              ");

echo "la table des utilisateurs a ete cree\n";


 /**************************************
    * Set initial data                 *
    **************************************/
 
    // Array with some test data to insert to database             
    $messages = array(
                  array('expediteur' => 1,
                        'destinataire' => 2,
                        'sujet' => 'Test!',
                        'message' => 'Juste Tester ma messagerie...',
                        'dateEnvoi' => '1327301464'),

                  array('expediteur' => 2,
                        'destinataire' => 1 ,
                        'sujet' => 'reponse au message de test!' ,
                        'message' => 'voici ma reponse, ta messagerie fonctionne?' ,
                        'dateEnvoi' => '1327307777'),

                  array('expediteur' => 1,
                        'destinataire' => 2,
                        'sujet' => 'parfait ca marche!' ,
                        'message' => 'Tout marche a merveille',
                        'dateEnvoi' => '1327214268')
                );


 $utilisateurs = array(
                  array('nomUtilisateur' => 'silver',
                        'motDePasse' => 'silver' ,
                        'role' => '1' ,
                        'activation' => '1'),
                        
                  array('nomUtilisateur' => 'emmanuel',
                        'motDePasse' => 'emmanuel' ,
                        'role' => '0' ,
                        'activation' => '1'),

                  array('nomUtilisateur' => 'toto',
                        'motDePasse' => 'toto' ,
                        'role' => '0' ,
                        'activation' => '0'),
                );



/**************************************
    * Play with databases and tables      *
    **************************************/



foreach ($messages as $m) {
        $formatted_time = date('Y-m-d H:i:s', $m['dateEnvoi']);
        $file_db->exec("INSERT INTO messages 
                VALUES (NULL,'{$m['expediteur']}','{$m['destinataire']}','{$m['sujet']}','{$m['message']}','{$formatted_time}' )");
}



foreach ($utilisateurs as $utilisateur) {
        $file_db->exec("INSERT INTO utilisateurs 
                VALUES (NULL,'{$utilisateur['nomUtilisateur']}','{$utilisateur['motDePasse']}','{$utilisateur['role']}','{$utilisateur['activation']}')");
}
 

echo "les tables ont ete rempli\n";
 
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }

?>
