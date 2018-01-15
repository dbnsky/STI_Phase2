<?php
try{
    $bddcon = new PDO('sqlite:/var/www/STI_Phase2/databases/database.sqlite');
    //$bddcon = new PDO('sqlite:/var/www/databases/database.sqlite');
    $bddcon->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION); 

  }
  catch(PDOException $e) {
    echo $e->getMessage();
  }


?>
