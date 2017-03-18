<?php
  class oApi {
    private $apiUrl;

    function setUrl($apiUrl) {
      /* Set Api URL for cUrl */
      $this->apiUrl = $apiUrl;
    }

    function sendRequest($paramArray) {
      /* Init cUrl request */
      $oReq = curl_init($this->apiUrl);

      /* Setup Request */
      curl_setopt($oReq, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($oReq, CURLOPT_SSLVERSION, 6);
      curl_setopt($oReq, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($oReq, CURLOPT_SSL_VERIFYHOST, 2);

      curl_setopt($oReq, CURLOPT_POST, true);
      curl_setopt($oReq, CURLOPT_POSTFIELDS, http_build_query($paramArray));

      /* Execute Request */
      $apiResp = curl_exec($oReq);
      parse_str($apiResp, $oApiResp);
      curl_close($oReq);

      /* Return answer as array */
      return $oApiResp;
    }
  }
?>
