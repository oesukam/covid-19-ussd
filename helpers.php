<?php
  // Reads the variables sent via POST from our gateway
  function getParam ($param) {
    if (isset($_POST[$param])) {
      return strval($_POST[$param]);
    }
    if (isset($_GET[$param])) {
      return strval($_GET[$param]);
    }
    throw new Exception("The index {$param} was undefined", 1);
  }

  function loadJson ($file_name) {
    $file_content = file_get_contents("./json/" . $file_name . ".json");
    return json_decode($file_content, true);
  }

  function formatResponse($content, $status = 'start') {
    if ($status == 'end') {
      return 'END ' . $content; // END to terminate the session
    } else {
      return 'CON ' . $content; // CON to continue the session
    }
  }
