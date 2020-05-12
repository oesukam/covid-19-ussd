<?php
  require './connection.php';
  require './helpers.php';

// The code has completely modified to fit on the need of COVID19-USSD project.

  $db = new Connection();

  $levels = loadJson('levels');
  $errors = loadJson('errors');
  
  $current_level = 1;

  $sessionId   = getParam("sessionId"); // This is a session unique value generated for every session
  $serviceCode =  getParam("serviceCode"); // This is your USSD code.
  $phoneNumber =  getParam("phoneNumber");// This is the mobile subscriber number|
  $text        =  getParam("text");// This shows the user input. It combines all the 
        

  
  $act = $db->findActivity($sessionId, $phoneNumber);

  if (!$act) {
    $db->insertActivity($sessionId, $phoneNumber, $serviceCode, $text, 1);
  } else {
    $current_level = $act['level'];
  }

  if (!$sessionId || !$serviceCode || !$phoneNumber || !$text) {
    $response = $errors['session_data'];
  } else {
    $response = handleMenu($db, $act, $levels);
  }

  // Echo the response back to the API
  header('Content-type: text/plain');
  echo $response;

?>