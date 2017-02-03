<?php
  session_start();

  /* Check if there is all requiered fields */

  $allOk = true;
  if (empty($_POST['gender'])) {
    $allOk = false;

  } elseif (empty($_POST['lastName'])) {
    $allOk = false;

  } elseif (empty($_POST['firstName'])) {
    $allOk = false;

  } elseif (empty($_POST['emailAddr'])) {
    $allOk = false;

  } elseif (empty($_POST['duration'])) {
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

  if (!$allOk) {
    header("Location: ./");
    exit;
  }

  /* Get payPal redirect url */
  $oReq = curl_init();
?>
