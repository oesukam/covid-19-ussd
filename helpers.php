<?php
  // Reads the variables sent via POST from our gateway
  function getParam ($param) {
    // Handle json  post request
    if ($_SERVER["CONTENT_TYPE"] == 'application/json') {
      $content = trim(file_get_contents("php://input"));
      $inputs = json_decode($content, true);

      if ($inputs[$param]) {
        return strval($inputs[$param]);
      }
    }

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

  function handleMenu ($db, $act, $levels) {
    $menu = '';
    $current_level = $act['level'];
    switch ($current_level) {
      case 1:
        $menu = formatResponse($levels[$current_level]);
        $db->updateLevel($act['id'], 2);
        break;
      case 2:
        $menu = formatResponse($levels[$current_level]);
        $db->updateLevel($act['id'], 3);
        break;
      default:
        $message = "Thank you for using our assistant";
        $menu = formatResponse($message, 'end');
        break;
    }

    return $menu;
  }
