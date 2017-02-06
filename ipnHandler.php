<?php
  $config = require("lib/config.php");

  function verifyIPN()
  {
    global $config;

    if ( ! count($_POST)) {
      error_log("Missing POST Data (" . $_SERVER['REMOTE_ADDR'] . ").");
      exit;
    }

    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = [];
    foreach ($raw_post_array as $keyval) {
      $keyval = explode('=', $keyval);
      if (count($keyval) == 2) {
        // Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
        if ($keyval[0] === 'payment_date') {
          if (substr_count($keyval[1], '+') === 1) {
            $keyval[1] = str_replace('+', '%2B', $keyval[1]);
          }
        }
        $myPost[$keyval[0]] = urldecode($keyval[1]);
      }
    }

    // Build the body of the verification post request, adding the _notify-validate command.
    $req = 'cmd=_notify-validate';
    $get_magic_quotes_exists = false;
    if (function_exists('get_magic_quotes_gpc')) {
      $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
      if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value));
      } else {
        $value = urlencode($value);
      }
      $req .= "&$key=$value";
    }

    // Post the data back to PayPal, using curl. Throw exceptions if errors occur.
    if($config['devMode']) {
      $curlReq = curl_init("https://ipnpb.sandbox.paypal.com/cgi-bin/webscr");
    } else {
      $curlReq = curl_init("https://ipnpb.paypal.com/cgi-bin/webscr");
    }
    curl_setopt($curlReq, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curlReq, CURLOPT_POST, 1);
    curl_setopt($curlReq, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlReq, CURLOPT_POSTFIELDS, $req);
    curl_setopt($curlReq, CURLOPT_SSLVERSION, 6);
    curl_setopt($curlReq, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curlReq, CURLOPT_SSL_VERIFYHOST, 2);

    curl_setopt($curlReq, CURLOPT_CAINFO, openssl_get_cert_locations()['default_cert_dir'] . "/ca-certificates.crt");
    curl_setopt($curlReq, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($curlReq, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curlReq, CURLOPT_HTTPHEADER, ['Connection: Close']);

    $res = curl_exec($curlReq);
    if ( ! ($res)) {
      $errno = curl_errno($curlReq);
      $errstr = curl_error($curlReq);
      curl_close($curlReq);
      error_log("cURL error: [$errno] $errstr");
      exit;
    }

    $info = curl_getinfo($curlReq);
    $http_code = $info['http_code'];
    if ($http_code != 200) {
      error_log("PayPal responded with http code $http_code");
      exit;
    }

    curl_close($curlReq);

    // Check if PayPal verifies the IPN data, and if so, return true.
    if ($res == "VERIFIED")
      return true;
    else
      return false;
  }

  /* Verify transaction source and informations */
  if (verifyIPN()) {

  }

  // Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
  header("HTTP/1.1 200 OK");
?>
