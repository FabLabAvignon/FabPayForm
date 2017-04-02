<?php
  $config = require(dirname(__FILE__) . "/conf/config.php");
  require(dirname(__FILE__) . "/lib/oApi.php");
  require(dirname(__FILE__) . "/lib/oDb.php");
  require(dirname(__FILE__) . "/lib/oLog.php");

  $oLog = new oLog();

  /* Check if we get PayPal infos */
  if(isset($_GET['token']) && isset($_GET['PayerID'])) {
    $oLog->infoLog("Incoming Payment !");
    $payToken = $_GET['token'];

    $oLog->infoLog("Validating payment..");
    $oApiReq = new oApi();
    $oApiReq->setUrl($config['devMode'] ? "https://api-3t.sandbox.paypal.com/nvp" : "https://api-3t.paypal.com/nvp");

    /* Get payment info */
    $checkOutInfos = $oApiReq->sendRequest(Array(
      /* Method & version */
      'METHOD' => 'GetExpressCheckoutDetails',
      'VERSION' => '204',

      /* Athentification */
      'USER' => $config['devMode'] ? $config['devApiCred']['user'] : $config['apiCred']['user'],
      'PWD' => $config['devMode'] ? $config['devApiCred']['pass'] : $config['apiCred']['pass'],
      'SIGNATURE' => $config['devMode'] ? $config['devApiCred']['signature'] : $config['apiCred']['signature'],

      'TOKEN' => $payToken,
    ));

    /* Execute payment (Note the array_merge, we reuse previous Api response...) */
    $oApiResp = $oApiReq->sendRequest(array_merge(
      Array(
        /* Method & version */
        'METHOD' => 'DoExpressCheckoutPayment',
        'VERSION' => '204',

        /* Athentification */
        'USER' => $config['devMode'] ? $config['devApiCred']['user'] : $config['apiCred']['user'],
        'PWD' => $config['devMode'] ? $config['devApiCred']['pass'] : $config['apiCred']['pass'],
        'SIGNATURE' => $config['devMode'] ? $config['devApiCred']['signature'] : $config['apiCred']['signature']
      ), $checkOutInfos));

    /* Prepare page */
    print("
    <!DOCTYPE html>
    <html>
      <head>
        <!-- Meta -->
        <meta charset=\"utf-8\">

        <!-- Title -->
        <title>Devenez adhérent</title>

        <!-- Stylesheets -->
        <link href=\"https://fonts.googleapis.com/css?family=Open+Sans\" rel=\"stylesheet\">
        <link rel=\"stylesheet\" href=\"assets/css/reset.css\">
        <link rel=\"stylesheet\" href=\"assets/css/style.css\">
      </head>
      <body>");

    if($oApiResp['ACK'] != "Success") {
      /* Display error page */
      $errorCode = $oApiResp['L_ERRORCODE0'];
      $errorMessage = $oApiResp['L_LONGMESSAGE0'];

      $oLog->errorLog("    Fail. (" . $errorCode . ": " . $errorMessage . ")");

      /* Display error page */
      print("
          <div class=\"container\">
            <h1>Oops, une erreur s'est produite !</h1>
            <br>
            <p>
              Nous sommes désolés, mais un erreur s'est produite.. Nous mettons tout en ordre pour résoudre votre problème !<br>
              L'erreur suivante s'est produite : (" . $errorCode . ": " . $errorMessage . ")
            </p>

            <div class=\"status\">
              <div class=\"failure\"></div>
            </div>
          </div>");
      exit;
    } else {
      $oLog->infoLog("    Success.");

      /* Success ! Call FabManager's api and execute flags script(s) if any */
      $oReq = $oDb->prepare('SELECT * FROM `fab-pay-form` WHERE `paymentId` = ?');
      $oReq->execute(Array($oApiResp['TOKEN']));

      $serverAnswer = $oReq->fetch();

      $oLog->infoLog("Adding client to FabManager with API..");
      /* Add client */
      $FabManagerAPI = new oApi();
      $FabManagerAPI->setUrl($config['fabApiUrl'] . '/user/create_account');
      $FabManagerReq = Array(
        'key' => $config['fabApiKey'],

        'gender' => ($serverAnswer['gender'] ? 'homme' : 'femme'),
        'familyName' => $serverAnswer['lastName'],
        'firstName' => $serverAnswer['firstName'],
        'email' => $serverAnswer['emailAddr'],
        'montant' => $config['payOptions'][$serverAnswer['membershipType']][0],
        'frequence' => $config['payOptions'][$serverAnswer['membershipType']][1],

        'birthday' => $serverAnswer['birthDate'],
        'address' => $serverAnswer['address'],
        'city' => $serverAnswer['city'],
        'postCode' => $serverAnswer['postCode'],
        'country' => $serverAnswer['country'],
        'tel' => $serverAnswer['phoneNumber']
      );
      $apiAnsw = $FabManagerAPI->sendRequest($FabManagerReq, true);

      /* Log error if any */
      if (!isset($apiAnsw['success'])) {
        $oLog->infoLog("    Fail. (" . print_r($apiAnsw, true) . ")");
      } else {
        $oLog->infoLog("    Success.");
      }

      $oLog->infoLog("Adding transaction to FabManager with API..");
      /* Add transaction */
      $FabManagerAPI->setUrl($config['fabApiUrl'] . '/cotisation/add');
      $FabManagerReq = Array(
        'key' => $config['fabApiKey'],

        'user_id' => $apiAnsw['user_id'],
        'price' => $config['payOptions'][$serverAnswer['membershipType']][0],
        'duration' => $config['payOptions'][$serverAnswer['membershipType']][1],
      );
      $apiAnsw = $FabManagerAPI->sendRequest($FabManagerReq, true);

      /* Log error if any */
      if (!isset($apiAnsw['success'])) {
        $oLog->infoLog("    Fail. (" . print_r($apiAnsw, true) . ")");
      } else {
        $oLog->infoLog("    Success.");
      }

      // Exec flags, later //

      // Send mail, later //

      /* Display success page */
      print("
      <div class=\"container\">
      <h1>Et voilà, c'est fait !</h1>
      <br>
      <p>
        Toute nos félicitations, vous êtes désormais l'un de nos membres. Bienvenue à Avilab, cher maker !
      </p>
      <div class=\"status\">
        <div class=\"success\"></div>
      </div>
    </div>
    <div class=\"go-back\">
      <a class=\"go-back\" href=\"http://www.avilab.fr\">Revenir sur avilab.fr</a>
    </div>\"");
    }
    /* End page */
    print("
        </body>
      </html>");
  } else {
    /* Redirect to main page if not $_GET infos */
    header("Location: ./");
    exit;
  }
?>
