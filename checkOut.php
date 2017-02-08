<?php
  require("lib/oPayPalAPI.php");
  $config = require("lib/config.php");

  $postFields = Array(
    /* Method & version */
    'METHOD' => 'GetExpressCheckoutDetails',
    'VERSION' => '204',

    /* Athentification */
    'USER' => $config['devMode'] ? $config['devApiCred']['user'] : $config['apiCred']['user'],
    'PWD' => $config['devMode'] ? $config['devApiCred']['pass'] : $config['apiCred']['pass'],
    'SIGNATURE' => $config['devMode'] ? $config['devApiCred']['signature'] : $config['apiCred']['signature'],

    'TOKEN' => $_GET['token'],
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
  parse_str($apiResp, $checkOutInfos);

  curl_close($oReq);

  $postFields = Array(
    /* Method & version */
    'METHOD' => 'DoExpressCheckoutPayment',
    'VERSION' => '204',

    /* Athentification */
    'USER' => $config['devMode'] ? $config['devApiCred']['user'] : $config['apiCred']['user'],
    'PWD' => $config['devMode'] ? $config['devApiCred']['pass'] : $config['apiCred']['pass'],
    'SIGNATURE' => $config['devMode'] ? $config['devApiCred']['signature'] : $config['apiCred']['signature'],

    'TOKEN' => $_GET['token'],
    'PAYERID' => $_GET['PayerID'],

    /* Item infos */
    'L_PAYMENTREQUEST_0_NAME0' => $checkOutInfos['L_PAYMENTREQUEST_0_NAME0'],
    'L_PAYMENTREQUEST_0_DESC0' => $checkOutInfos['L_PAYMENTREQUEST_0_DESC0'],
    'L_PAYMENTREQUEST_0_AMT0' => $checkOutInfos['L_PAYMENTREQUEST_0_AMT0'],
    'L_PAYMENTREQUEST_0_QTY0' => 1,

    'PAYMENTREQUEST_0_ITEMAMT' => $checkOutInfos['PAYMENTREQUEST_0_ITEMAMT'],

    'PAYMENTREQUEST_0_AMT' => $checkOutInfos['PAYMENTREQUEST_0_AMT'],
    'PAYMENTREQUEST_0_CURRENCYCODE' => $checkOutInfos['PAYMENTREQUEST_0_CURRENCYCODE'],
    'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',

    'PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID' => $checkOutInfos['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID']
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

  print_r($oApiResp);
 ?>
