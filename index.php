<?php
  require './connection.php';

// The code has completely modified to fit on the need of COVID19-USSD project.
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


  $db = new Connection();

  echo $db->version() . "\n";

 

  $results = $db->getAllSessions();

  echo "Session ID\tPhone number\n";
  while ($row = $results->fetchArray())
  {
    echo $row['session_id'] . "\t\t" . $row['phone_number'] . "\n";
  }


  $sessionId   = getParam("sessionId"); // This is a session unique value generated for every session
  $serviceCode =  getParam("serviceCode"); // This is your USSD code.
  $phoneNumber =  getParam("phoneNumber");// This is the mobile subscriber number|
  $text        =  getParam("text");// This shows the user input. It combines all the 
        

  $act = $db->findActivity($sessionId, $phoneNumber);

  if (!$act) {
    $db->insertActivity($sessionId, $phoneNumber, $serviceCode, $text, 1);
  }


  $response = "\nSession ID: {$sessionId}\n";
  $response =  $response . "Service code: {$serviceCode}\n";
  $response = $response . "Phone number: {$phoneNumber}\n";
  $response = $response . "Input: {$text}";

  // Echo the response back to the API
  header('Content-type: text/plain');
  echo $response;

?>