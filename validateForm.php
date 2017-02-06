<?php
  $config = require("lib/config.php");

  /* Check if there is all requiered fields */
  if(!count($_POST)) {
    header("Location: ./");
    exit;
  }

  $allOk = true;
  if (empty($_POST['gender'])) {
    $allOk = false;

  } elseif (empty($_POST['lastName'])) {
    $allOk = false;

  } elseif (empty($_POST['firstName'])) {
    $allOk = false;

  } elseif (empty($_POST['emailAddr'])) {
    $allOk = false;

  } elseif (empty($_POST['membershipType'])) {
    $allOk = false;

  } /*elseif ($_POST['']) {
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

  /*if (!$allOk) {
    header("Location: ./");
    exit;
  }*/

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

  /* Get payPal redirect url */
  if($config['devMode']) {
    $oReq = curl_init("https://www.sandbox.paypal.com/cgi-bin/webscr");
  } else {
    $oReq = curl_init("https://www.paypal.com/cgi-bin/webscr");
  }

  $transId = NewTransId();

  /* Build PayPal request */ // Needs some explanation later..
  $postFields = Array(
    'cmd' => '_xclick',
    'business' => $config['devMode'] ? $config['devPayEmail'] : $config['payEmail'],
    'lc' => 'FR',
    'notify_url' => dirname(
      isset($_SERVER['HTTPS']) ? 'https://' : 'http://'
      . $_SERVER['SERVER_NAME']
      . $_SERVER['REQUEST_URI'])
      . '/ipnHandler.php',
    'return' => dirname(
      isset($_SERVER['HTTPS']) ? 'https://' : 'http://'
      . $_SERVER['SERVER_NAME']
      . $_SERVER['REQUEST_URI'])
      . '/paySuccess.php',
    'rm' => 2,
    'item_name' => $config['itemName'],
    'button_subtype' => 'services',
    'no_note' => 1,
    'no_shipping' => 1,
    'on0' => 'Type',
    'os0' => $_POST['membershipType'],
    'option_select0' => $_POST['membershipType'],
    'option_amount0' => $config['payOptions'][$_POST['membershipType']][0],
    'on1' => 'TransUID',
    'os1' => $transId,
    'option_index' => 0,
    'bn' => 'PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted',
    'currencyCode' => 'EUR'
  );

  curl_setopt($oReq, CURLOPT_HEADER, true);
  curl_setopt($oReq, CURLOPT_NOBODY, true);
  curl_setopt($oReq, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($oReq, CURLOPT_COOKIESESSION, true);

  curl_setopt($oReq, CURLOPT_SSLVERSION, 6);
  curl_setopt($oReq, CURLOPT_SSL_VERIFYPEER, 1);
  curl_setopt($oReq, CURLOPT_SSL_VERIFYHOST, 2);

  curl_setopt($oReq, CURLOPT_POST, true);
  curl_setopt($oReq, CURLOPT_POSTFIELDS, http_build_query($postFields));

  $headVar = curl_exec($oReq);
  $oHead = explode("\n", $headVar);

  curl_close($oReq);

  /* Get location in header */
  for($i=0; $i < count($oHead); $i++)
    if (strpos($oHead[$i], "Location:") !== false)
      $reqLoc = (substr($oHead[$i], strpos($oHead[$i], "Location:") + strlen("Location:")));

  /* Proxy cookies from paypal to client */
  for($i=0; $i < count($oHead); $i++)
    if (strpos($oHead[$i], "Set-Cookie:") !== false)
      header($oHead[$i], false);
      //echo $oHead[$i];

  header("Location: " . $reqLoc, false);
  exit;

  /* Put all infos in database */

?>
