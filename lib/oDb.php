<?php
  try {
    $oDb = new PDO('mysql:host=' . $config['dataBaseConf']['dbHost']
      . ';port=' . $config['dataBaseConf']['dbPort']
      . ';dbname=' . $config['dataBaseConf']['dbName']
      . ';charset=utf8', $config['dataBaseConf']['dbUser'], $config['dataBaseConf']['dbPass']);
  } catch(Exception $e) {
    die("Database connection fail !");
    exit;
  }
?>
