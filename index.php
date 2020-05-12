<?php
  require './connection.php';
  require './helpers.php';

// The code has completely modified to fit on the need of COVID19-USSD project.

  try {

    $db = new Connection();

    $levels = loadJson('levels');
    $errors = loadJson('errors');
    
    $current_level = 1;

    $session_id   = getParam("sessionId"); // This is a session unique value generated for every session
    $service_code =  getParam("serviceCode"); // This is your USSD code.
    $phone_number =  getParam("phoneNumber");// This is the mobile subscriber number|
    $text        =  getParam("text");// This shows the user input. It combines all the 
          
    $act = $db->findActivity($session_id, $phone_number);

    if (!$act) {
      $db->insertActivity($session_id, $phone_number, $service_code, $text, 1);
    } else {
      $current_level = $act['level'];
    }

    if (!$session_id || !$service_code || !$phone_number || !$text) {
      $response = $errors['session_data'];
    } else {
      $response = handleMenu($db, $act, $levels);
    }

    // Echo the response back to the API
    header('Content-type: text/plain');

    echo $response;
  } catch(Exception $e) {
    header('Content-type: text/plain');
    echo 'Caught exception: ',  $e->getMessage(), "\n";
  }

?>