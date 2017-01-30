<?php
  /* If there is no POST data */
  if(empty($_POST)) {
    header("Location: ./"); // Redirect to form
    exit;
  }

  echo var_dump($_POST);

?>
