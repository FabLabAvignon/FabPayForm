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
  $transId = NewTransId();

  /* Put all infos in database */
    // Later

  /* Get payPal redirect url */
  if($config['devMode']) {
    $reqUrl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
  } else {
    $reqUrl = "https://www.paypal.com/cgi-bin/webscr";
  }

  /* Build PayPal request */
  $postFields = Array(
    /* Command type */
    'cmd' => '_xclick',
    /* PayPal seller email */
    'business' => ($config['devMode'] ? $config['devPayEmail'] : $config['payEmail']),
    /* PayPal display locale
     * -> https://developer.paypal.com/docs/classic/api/locale_codes/
     */
    'lc' => 'FR',

    /* IPN Handler Url to automate payment handling */
    'notify_url' => dirname(
      (isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
      . $_SERVER['SERVER_NAME']
      . $_SERVER['REQUEST_URI'])
      . '/ipnHandler.php',

    /* Url where customer is redirected after successful payment */
    'return' => dirname(
      (isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
      . $_SERVER['SERVER_NAME']
      . $_SERVER['REQUEST_URI'])
      . '/paySuccess.php',
    /* Set return mode to POST, anyway,
     * must be an HTTPS Url otherwise it will throw a warning.
     */
    'rm' => 2,

    /* Item name displayed on PayPal customer account */
    'item_name' => $config['itemName'],

    /* Disable notes & shipping address */
    'no_note' => 1,
    'no_shipping' => 1,

    /* Passing params to PayPal,
     * some are irrevelant, but needed,
     * some are absolutly necessary
     */
    'on0' => 'Type',
    'os0' => $_POST['membershipType'],
    'option_select0' => $_POST['membershipType'],
    'option_amount0' => $config['payOptions'][$_POST['membershipType']][0],
    'on1' => 'TransUID',
    'os1' => $transId,

    /* Currency code from config */
    'currency_code' => $config['payOptions']['currencyCode']
  );

  /* Redirect client to PayPal */
  header("Location: " . $reqUrl . "?" . http_build_query($postFields));
?>
