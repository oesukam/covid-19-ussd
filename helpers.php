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

  function checkInput($level = 1, $input) {
    $levels_choices = loadJson('levels-choices');
    if (!array_key_exists($level, $levels_choices)) {
      return true;
    }

    if (!preg_match($levels_choices[$level], $input)) {
      throw new Exception("Wrong input", 1); 
    }

    return true;
  }

  function handleMenu ($db, $act, $levels, $input) {
    $menu = '';
    $current_level = $act['level'];
    switch ($current_level) {
      case 1:
        $menu = formatResponse($levels[$current_level]);
        $act['level'] = ++$act['level'];
        $act["level_{$current_level}_input"] = $input;
        $db->updateActivity($act);
        break;
      case 2:
        checkInput($current_level, $input);
        $menu = formatResponse($levels[$current_level]);
        $act['level'] = ++$act['level'];
        $act["level_{$current_level}_input"] = $input;
        $db->updateActivity($act);
        break;
      case 3:
          checkInput($current_level, $input);
          $menu = formatResponse($levels[$current_level]);
          $act['level'] = ++$act['level'];
          $act["level_{$current_level}_input"] = $input;
          $db->updateActivity($act);
          break;
      default:
        checkInput($current_level, $input);
        $message = "Thank you for using our assistant";
        $menu = formatResponse($message, 'end');
        $act['status'] = 'terminated';
        $db->updateActivity($act);
        break;
    }

    return $menu;
  }
