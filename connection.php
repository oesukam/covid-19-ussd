<?php

  class Connection {
    private $db_name;
    private $db;

    function __construct($db_name = 'db.sqlite') {
      $this->db_name = $db_name;
      $this->db = new SQLite3($db_name);
    }

    public function findActivity($session_id, $phone_number) {
      $query = "SELECT * FROM sessions WHERE session_id = '{$session_id}' AND phone_number = '{$phone_number}' LIMIT 1";
      $res = $this->db->query($query);
      
      return $res->fetchArray(1);
    }

    public function getAllSessions() {
      return $this->db->query('SELECT * FROM sessions');
    }

    public function insertActivity($session_id, $phone_number, $service_code, $input, $level = 1) {
      $query = "INSERT INTO sessions (session_id, phone_number, service_code, input, level) VALUES (?, ?, ?, ?, ?)";
      $stm = $this->db->prepare($query);
      $stm->bindParam(1, $session_id);
      $stm->bindParam(2, $phone_number);
      $stm->bindParam(3, $service_code);
      $stm->bindParam(4, $input);
      $stm->bindParam(5, $level);

      return $stm->execute();
    }

    public function updateLevel($id, $level) {
      $query = "UPDATE sessions SET level = ? WHERE id = ?";
      $stm = $this->db->prepare($query);
      $stm->bindParam(1, $level);
      $stm->bindParam(2, $id);

      return $stm->execute();
    }

    public function version () {
      return $this->db->querySingle('SELECT SQLITE_VERSION()');
    }
  }

  