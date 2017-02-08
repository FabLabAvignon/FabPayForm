<?php
  $config = require("lib/config.php");

  /* Generate transUniqueID */
  function NewTransId() {
    $s = strtoupper(md5(uniqid(rand(),true)));
    $guidText =
      substr($s,0,8) . '-' .
      substr($s,8,4) . '-' .
      substr($s,12,4). '-' .
      substr($s,16,4). '-' .
      substr($s,20);
    return $guidText;
  }

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
    $transId = NewTransId();

    /* Setup Params */
    $postFields = Array(
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
      'RETURNURL' => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
        . $_SERVER['SERVER_NAME']
        . dirname($_SERVER['REQUEST_URI'])
        . '/checkOut.php',
      'CANCELURL' => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
        . $_SERVER['SERVER_NAME']
        . dirname($_SERVER['REQUEST_URI'])
        . '/',

      'PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID' => $config['devMode'] ? $config['devPayEmail'] : $config['payEmail']
    );

    /* Create API Request */
    if($config['devMode']) {
      $oReq = curl_init("https://api-3t.sandbox.paypal.com/nvp");
    } else {
      $oReq = curl_init("https://api-3t.paypal.com/nvp");
    }

    /* Setup Request */
    curl_setopt($oReq, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($oReq, CURLOPT_SSLVERSION, 6);
    curl_setopt($oReq, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($oReq, CURLOPT_SSL_VERIFYHOST, 2);

    curl_setopt($oReq, CURLOPT_POST, true);
    curl_setopt($oReq, CURLOPT_POSTFIELDS, http_build_query($postFields));

    /* Execute Request */
    $apiResp = curl_exec($oReq);
    parse_str($apiResp, $oApiResp);

    curl_close($oReq);

    if($oApiResp['ACK'] != 'Success') {
      /* An error happened */
      header("Location: ./");
      exit;
    }

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
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Scripts -->
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script defer src="js/main.js"></script>
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
          <input type="text" name="lastName" placeholder="Votre nom de famille">
        </div>
        <div class="input-block">
          <label for="firstName">Prénom</label><br>
          <input type="text" name="firstName" placeholder="Votre prénom">
        </div>
        <div class="input-block">
          <label for="emailAddr">Email</label><br>
          <input type="email" name="emailAddr" placeholder="Votre email">
        </div>

        <div class="input-block">
          <label for="membershipType">Durée de votre adhésion</label><br>
          <?php
            foreach($config['payOptions'] as $key => $infos) {
              if ($key != 'currencyCode') {
                echo "<input type=\"radio\" name=\"membershipType\" value=\"" . $key . "\">" . $key . " - " . $infos[0] . " " . $config['payOptions']['currencyCode'] . "</option><br>";
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
          <input type="date" name="birthDate" placeholder="jj/mm/aaaa">
        </div>
        <div class="input-block">
          <label for="address">Adresse</label><br>
          <input type="text" name="address" placeholder="Votre adresse">
        </div>
        <div class="input-block">
          <label for="city">Ville</label><br>
          <input type="text" name="city" placeholder="Votre ville">
        </div>
        <div class="input-block">
          <label for="postCode">Code postal</label><br>
          <input type="text" name="postCode" placeholder="Votre code postal">
        </div>
        <div class="input-block">
          <label for="country">Pays</label><br>
          <input type="text" name="country" placeholder="Votre pays">
        </div>
        <div class="input-block">
          <label for="phoneNum">Téléphone</label><br>
          <input type="text" name="phoneNum" placeholder="Votre téléphone">
        </div>

        <button type="submit">Adhérer</button>
      </form>
    </div>
  </body>
</html>
