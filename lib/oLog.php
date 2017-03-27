<?php
  $config = require(dirname(__FILE__) . "/../conf/config.php");

  class oLog
  {
    private $logHandler;
    private $devMode;

    function __construct() {
      global $config;
      $this->logHandler = fopen(dirname(__FILE__) . "/../" . $config['logFile'], "a+");
      $this->devMode = $config['devMode'];
    }

    function __destruct() {
      fclose($this->logHandler);
    }

    function infoLog($message) {
      if ($this->devMode)
        fwrite($this->logHandler, "!Dev! ");

      fwrite($this->logHandler,
        "["
        . gmdate("d-M-y H:i:s T")
        . "] [Info] ("
        . basename($_SERVER['PHP_SELF'])
        . ") "
        . $message
        . "\n");
    }

    function errorLog($message) {
      if ($this->devMode)
        fwrite($this->logHandler, "!Dev! ");

        fwrite($this->logHandler,
          "["
          . gmdate("d-M-y H:i:s T")
          . "] [Error] ("
          . basename($_SERVER['PHP_SELF'])
          . ") "
          . $message
          . "\n");
    }
  }
?>
