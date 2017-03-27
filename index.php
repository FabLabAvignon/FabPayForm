<?php
  $config = require(dirname(__FILE__) . "/conf/config.php");
  require(dirname(__FILE__) . "/lib/oApi.php");
  require(dirname(__FILE__) . "/lib/oDb.php");
  require(dirname(__FILE__) . "/lib/oLog.php");

  $oLog = new oLog();

  /* Check if there is all required fields */
  $allOk = true;

  $checkFields = Array(
    'gender' => Array(
      'required' => true,
      'regex' => ''
    ),
    'lastName' => Array(
      'required' => true,
      'regex' => '/^.{3,}$/i'
    ),
    'firstName' => Array(
      'required' => true,
      'regex' => '/^.{3,}$/i'
    ),
    'emailAddr' => Array(
      'required' => true,
      'regex' => '/^[a-z0-9._%-]+@[a-z0-9.-]+.[a-z]{2,4}$/i'
    ),
    'membershipType' => Array(
      'required' => true,
      'regex' => ''
    ),
    'birthDate' => Array(
      'required' => false,
      'regex' => '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/'
    ),
    'address' => Array(
      'required' => false,
      'regex' => ''
    ),
    'city' => Array(
      'required' => false,
      'regex' => ''
    ),
    'postCode' => Array(
      'required' => false,
      'regex' => '/^[0-9]{5}$/'
    ),
    'country' => Array(
      'required' => false,
      'regex' => ''
    ),
    'phoneNumber' => Array(
      'required' => false,
      'regex' => '/^[0-9]{10}$/'
    )
  );

  foreach ($checkFields as $fieldName => $fieldParams) {
    /* If the field is required but empty */
    if(empty($_POST[$fieldName]) && $fieldParams['required']) {
      $allOk = false;
      break;
    }

    /* If the field is non-empty */
    if(!empty($_POST[$fieldName])) {
      /* If no regex for field */
      if (empty($fieldParams['regex']))
        continue;

      /* Check regex */
      if(!preg_match($fieldParams['regex'], $_POST[$fieldName])) {
        $allOk = false;
        break;
      }
    }
  }

  if ($allOk) {
    $oLog->infoLog("Validated all form inputs");

    $oLog->infoLog("Sending API request to PayPal..");
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
      $oLog->errorLog("    Fail. (" . $errorCode . ": " . $errorMessage . ")");

      /* Display error page */
      $errorCode = $oApiResp['L_ERRORCODE0'];
      $errorMessage = $oApiResp['L_LONGMESSAGE0'];

      print("Oh crap, an error occured ! (" . $errorCode . ": " . $errorMessage . ")");
      exit;
    }
    $oLog->infoLog("    Success.");

    /* Success ! Adding entry to database */
    $oReq = $oDb->prepare('INSERT INTO `fab-pay-form`
      (`paymentId`,
       `gender`,
       `lastName`,
       `firstName`,
       `emailAddr`,
       `membershipType`,
       `birthDate`,
       `address`,
       `city`,
       `postCode`,
       `country`,
       `phoneNumber`)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
    $oReq->execute(Array(
      $oApiResp['TOKEN'],
      $_POST['gender'],
      $_POST['lastName'],
      $_POST['firstName'],
      $_POST['emailAddr'],
      $_POST['membershipType'],
      date("Y-m-d", strtotime($_POST['birthDate'])),
      $_POST['address'],
      $_POST['city'],
      $_POST['postCode'],
      $_POST['country'],
      $_POST['phoneNum']
    ));

    /* Redirect client to PayPal */
    $oLog->infoLog("Redirecting to PayPal gateway");
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

      <form id="register-form" action="" method="POST">

        <!-- Required -->
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
          <input type="text" id="lastName" name="lastName" placeholder="Ex : Gershenfeld">
        </div>
        <div class="input-block">
          <label for="firstName">Prénom</label><br>
          <input type="text" id="firstName" name="firstName" placeholder="Ex : Neil">
        </div>
        <div class="input-block">
          <label for="emailAddr">Email</label><br>
          <input type="email" id="emailAddr" name="emailAddr" placeholder="Ex : neil.gershenfeld@gmail.com">
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

        <!-- Optionals -->
        <div class="input-block">
          <label for="birthDate">Date de naissance</label><br>
          <input type="text" id="birthDate" name="birthDate" placeholder="jj/mm/aaaa">
        </div>
        <div class="input-block">
          <label for="address">Adresse</label><br>
          <input type="text" id="address" name="address" placeholder="Ex : 14 bis Impasse Lescure,">
        </div>
        <div class="input-block">
          <label for="city">Ville</label><br>
          <input type="text" id="city" name="city" placeholder="Ex : Avignon">
        </div>
        <div class="input-block">
          <label for="postCode">Code postal</label><br>
          <input type="text" id="postCode" name="postCode" placeholder="Ex : 84000">
        </div>
        <div class="input-block">
          <label for="country">Pays</label><br>
          <input type="text" id="country" name="country" placeholder="Ex : France">
        </div>
        <div class="input-block">
          <label for="phoneNum">Téléphone</label><br>
          <input type="text" id="phoneNum" name="phoneNum" placeholder="Ex : 0622984676">
        </div>

        <button class="submit-button<?php if($config['devMode']) echo ' devmode'; ?>" type="submit">Adhérer<?php if($config['devMode']) echo '<br>(Le site est en cours de maintenance, merci de votre conprehension.)'; ?></button>
      </form>
    </div>
  </body>
</html>
