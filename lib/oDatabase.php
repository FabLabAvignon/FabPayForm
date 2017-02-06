<?php
  $config = require("lib/config.php");

  try {
    $oDatabase = new PDO('mysql:host=' . $config['dataBaseConf']['dbHost']
      . ';port=' . $config['dataBaseConf']['dbPort']
      . ';dbname=' . $config['dataBaseConf']['dbName']
      . ';charset=utf8', $config['dataBaseConf']['dbUser'], $config['dataBaseConf']['dbPass']);
  } catch(Exception $e) {
    error_log("Database connection fail !");
    exit;
  }

  return $oDatabase;
?>
