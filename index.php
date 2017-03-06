<?php
  require("lib/oApi.php");
  $config = require("lib/config.php");

  /* Check if there is all requiered fields */
  $allOk = true;
  if (!count($_POST)) {
    $allOk = false;

  }/* elseif (empty($_POST['gender'])) {
    $allOk = false;

  } elseif (empty($_POST['lastName'])) {
    $allOk = false;

  } elseif (empty($_POST['firstName'])) {
    $allOk = false;

  } elseif (empty($_POST['emailAddr'])) {
    $allOk = false;

  } */elseif (empty($_POST['membershipType'])) {
    $allOk = false;

  }/* elseif ($_POST['']) {
    $allOk = false;

  } elseif ($_POST['']) {
    $allOk = false;

  } elseif ($_POST['']) {
    $allOk = false;

  } elseif ($_POST['']) {
    $allOk = false;

  } elseif ($_POST['']) {
    $allOk = false;

  }*/

  if ($allOk) {
    $oApiReq = new oApi();
    $oApiReq->setUrl($config['devMode'] ? "https://api-3t.sandbox.paypal.com/nvp" : "https://api-3t.paypal.com/nvp");

    /* Initiate payment and get token */
    $oApiResp = $oApiReq->sendRequest(Array(
      /* Method & version */
      'METHOD' => 'SetExpressCheckout',
      'VERSION' => '204',

      /* Athentification */
      'USER' => $config['devMode'] ? $config['devApiCred']['user'] : $config['apiCred']['user'],
      'PWD' => $config['devMode'] ? $config['devApiCred']['pass'] : $config['apiCred']['pass'],
      'SIGNATURE' => $config['devMode'] ? $config['devApiCred']['signature'] : $config['apiCred']['signature'],

      /* Item infos */
      'L_PAYMENTREQUEST_0_NAME0' => $config['itemName'],
      'L_PAYMENTREQUEST_0_DESC0' => $_POST['membershipType'],
      'L_PAYMENTREQUEST_0_AMT0' => $config['payOptions'][$_POST['membershipType']][0],
      'L_PAYMENTREQUEST_0_QTY0' => 1,

      'PAYMENTREQUEST_0_ITEMAMT' => $config['payOptions'][$_POST['membershipType']][0],

      'PAYMENTREQUEST_0_AMT' => $config['payOptions'][$_POST['membershipType']][0],
      'PAYMENTREQUEST_0_CURRENCYCODE' => $config['payOptions']['currencyCode'],
      'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',

      'NOSHIPPING' => 1,
      'ALLOWNOTE' => 0,

      /* Misc infos */
      'RETURNURL' =>  dirname((isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
        . $_SERVER['SERVER_NAME']
        . $_SERVER['SCRIPT_NAME'])
        . '/checkOut.php',
      'CANCELURL' =>  dirname((isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
        . $_SERVER['SERVER_NAME']
        . $_SERVER['SCRIPT_NAME'])
        . '',

      'PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID' => $config['devMode'] ? $config['devPayEmail'] : $config['payEmail']
    ));

    if($oApiResp['ACK'] != "Success") {
      /* Display error page */
      $errorCode = $oApiResp['L_ERRORCODE0'];
      $errorMessage = $oApiResp['L_LONGMESSAGE0'];

      print("Oh crap, an error occured ! (" . $errorCode . ": " . $errorMessage . ")");
      exit;
    }

    /* Success ! Adding entry to database */
      // Later

    /* Redirect client to PayPal */
    header("Location: " . ($config['devMode'] ? "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=" : "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=") . $oApiResp['TOKEN']);
    exit;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Meta -->
    <meta charset="utf-8">

    <!-- Title -->
    <title>Devenez adhérent</title>

    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Scripts -->
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script defer src="assets/js/main.js"></script>
  </head>
  <body>
    <div class="container">
      <h1>Oh, alors vous voulez devenir un maker, hein ?</h1>
      <br>
      <p>Vous y êtes presque ! Pour devenir adhérent, remplissez le formulaire
      ci dessous...</p>

      <form action="" method="POST">

        <!-- Requiered  -->
        <div class="sticker">
          <span class="text">Le minimum syndical...</span>
          <div class="arrow-right"></div>
        </div>
        <p class="hint">Les informations ci-dessous sont obligatoire.</p>
        <div class="input-block">
          <label for="gender">Civilité</label><br>
          <input type="radio" name="gender" value="man"> Mr<br>
          <input type="radio" name="gender" value="woman"> Mme
        </div>
        <div class="input-block">
          <label for="lastName">Nom</label><br>
          <input type="text" id="lastName" name="lastName" placeholder="Votre nom de famille">
        </div>
        <div class="input-block">
          <label for="firstName">Prénom</label><br>
          <input type="text" id="firstName" name="firstName" placeholder="Votre prénom">
        </div>
        <div class="input-block">
          <label for="emailAddr">Email</label><br>
          <input type="email" id="emailAddr" name="emailAddr" placeholder="Votre email">
        </div>

        <div class="input-block">
          <label for="membershipType">Durée de votre adhésion</label><br>
          <?php
            foreach($config['payOptions'] as $key => $infos) {
              if ($key != 'currencyCode') {
                echo "<input type=\"radio\" name=\"membershipType\" value=\"" .
                      $key . "\">" . $key . " - " . $infos[0] . " " .
                      $config['payOptions']['currencyCode'] . "</option><br>";
              }
            }
          ?>
        </div>

        <div class="sticker">
          <span class="text">Vous êtes bavard ?</span>
          <div class="arrow-right"></div>
        </div>
        <p class="hint">Les informations ci-dessous sont facultatives.</p>

        <!-- Optionals  -->
        <div class="input-block">
          <label for="birthDate">Date de naissance</label><br>
          <input type="date" id="birthDate" name="birthDate" placeholder="jj/mm/aaaa">
        </div>
        <div class="input-block">
          <label for="address">Adresse</label><br>
          <input type="text" id="address" name="address" placeholder="Votre adresse">
        </div>
        <div class="input-block">
          <label for="city">Ville</label><br>
          <input type="text" id="city" name="city" placeholder="Votre ville">
        </div>
        <div class="input-block">
          <label for="postCode">Code postal</label><br>
          <input type="text" id="postCode" name="postCode" placeholder="Votre code postal">
        </div>
        <div class="input-block">
          <label for="country">Pays</label><br>
          <input type="text" id="country" name="country" placeholder="Votre pays">
        </div>
        <div class="input-block">
          <label for="phoneNum">Téléphone</label><br>
          <input type="text" id="phoneNum" name="phoneNum" placeholder="Votre téléphone">
        </div>

        <button type="submit">Adhérer</button>
      </form>
    </div>
  </body>
</html>
