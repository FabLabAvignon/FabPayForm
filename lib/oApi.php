<?php
  class oApi {
    private $apiUrl;

    function setUrl($apiUrl) {
      /* Set Api URL for cUrl */
      $this->apiUrl = $apiUrl;
    }

    function sendRequest($paramArray, $jsonEncode = false) {
      /* Init cUrl request */
      $oReq = curl_init($this->apiUrl);

      /* Setup Request */
      curl_setopt($oReq, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($oReq, CURLOPT_SSLVERSION, 6);
      curl_setopt($oReq, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($oReq, CURLOPT_SSL_VERIFYHOST, 2);

      curl_setopt($oReq, CURLOPT_POST, true);
      if ($jsonEncode) {
        curl_setopt($oReq, CURLOPT_POSTFIELDS, json_encode($paramArray, JSON_NUMERIC_CHECK));
      } else {
        curl_setopt($oReq, CURLOPT_POSTFIELDS, http_build_query($paramArray));
      }

      /* Execute Request */
      $apiResp = curl_exec($oReq);
      curl_close($oReq);

      /* Transform answer to Array */
      if ($jsonEncode) {
        $oApiResp = json_decode($apiResp, true);
      } else {
        parse_str($apiResp, $oApiResp);
      }

      /* Return answer as array */
      return $oApiResp;
    }
  }
?>
