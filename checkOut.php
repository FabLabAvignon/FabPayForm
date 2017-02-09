<?php
  require("lib/oApi.php");
  $config = require("lib/config.php");

  /* Check if we get PayPal infos */
  if(isset($_GET['token']) && isset($_GET['PayerID'])) {
    $payToken = $_GET['token'];

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

    if($oApiResp['ACK'] != "Success") {
      /* Display error page */
      $errorCode = $oApiResp['L_ERRORCODE0'];
      $errorMessage = $oApiResp['L_LONGMESSAGE0'];

      print("Oh crap, an error occured ! (" . $errorCode . ": " . $errorMessage . ")");
      exit;
    }

    /* Success ! Call FabManager's api and execute flags script(s) if any */
      // Later

    /* Display success page */
    print("Amazing Success Page !");
  } else {
    /* Redirect to main page if not $_GET infos */
    header("Location: ./");
    exit;
  }
?>
