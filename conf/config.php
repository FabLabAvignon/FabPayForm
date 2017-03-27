<?php
  return Array(
    /* The name displayed on the PayPal buyer account */
    'itemName' => 'FabLab Membership',

    /*
     * Define durations with price and label, basically this is the patern :

        '<label>' => Array(
          '<price>',
          '<duration>',
          '<flags>'
        ),

     * With price as a float (Eg. 3.00),
     * duration in month (Eg. 1 => 1 Month),
     * and the flags as a single or multiple chars (Eg. 'a' or 'zab').
     * Note : Any flag will result in an execution of the script placed
     * in 'lib/flags/<flag>.php'.
     */
    'payOptions' => Array(
      /* PayPal currency code (E.g. EUR, USD, AUD)
       * -> https://developer.paypal.com/docs/classic/api/currency_codes/
       */
      'currencyCode' => 'EUR',
      '1 An - Etudiant' => Array(
        '60.00',
        '12',
        ''
      ),
      '1 An - Salarié' => Array(
        '150.00',
        '12',
        ''
      )
    ),

    /* PayPal pay email */
    'payEmail' => 'paypal@avilab.fr',

    /* API credentials for PayPal requests,
     * to generate them, follow this guide :
     * -> https://developer.paypal.com/docs/classic/api/apiCredentials/#create-an-api-signature
     */
    'apiCred' => Array(
      'user' => '<apiUser>',
      'pass' => '<apiPass>',
      'signature' => '<apiSignature>'
    ),

    /* FabManager API Key & API Url */
    'fabApiKey' => '<apiKey>',
    'fabApiUrl' => '<apiUrl>', // E.g. https://fabmanager.yourdomain.tld/api

    /* Database infos */
    'dataBaseConf' => Array(
      'dbUser' => '<userName>',
      'dbPass' => '<passPhrase>',
      'dbName' => '<dbName>',
      'dbHost' => '127.0.0.1',
      'dbPort' => '3306'
    ),

    /* Mail server conf */
    'mailConf' => Array(
      'host' => '<mailHost>',
      'user' => '<mailUser>',
      'pass' => '<mailPass>'
    ),

    /* Dev mode */
    'devMode' => true,
    'logFile' => 'conf/log.log',
    'devPayEmail' => '<devPayEmail>',
    'devApiCred' => Array(
      'user' => '<apiUser>',
      'pass' => '<apiPass>',
      'signature' => '<apiSignature>'
    )
  );
?>
