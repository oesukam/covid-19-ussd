<?php

  class Connection {
    private $db_name;
    private $db;

    function __construct($db_name = 'db.sqlite') {
      try {
        $this->db_name = $db_name;
        $this->db  = new PDO("sqlite:{$db_name}");
        $table = 'CREATE TABLE "sessions" (
          "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
          "session_id" TEXT,
          "phone_number" TEXT,
          "input" TEXT,
          "level" integer NOT NULL DEFAULT 1,
          "status" TEXT NOT NULL DEFAULT active,
          "service_code" TEXT,
          "level_1_input" TEXT,
          "level_2_input" TEXT,
          "level_3_input" TEXT,
          "level_4_input" TEXT,
          "level_5_input" TEXT
        )';

        $this->db->exec($table);
      }
      catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
      }
    }

    public function findActivity($session_id, $phone_number) {
      $query = "SELECT * FROM sessions WHERE session_id = '{$session_id}' AND phone_number = '{$phone_number}' LIMIT 1";
      $res = $this->db->query($query);
      return $res->fetch();
    }

    public function getAllSessions() {
      return $this->db->query('SELECT * FROM sessions')->fetchAll();
    }

    public function insertActivity($session_id, $phone_number, $service_code, $input, $level = 1) {
      $query = "INSERT INTO sessions (session_id, phone_number, service_code, input, level) VALUES (?, ?, ?, ?, ?)";
      $stmt = $this->db->prepare($query);
      return $stmt->execute([$session_id, $phone_number, $service_code, $input, $level = 1]);; 
    }

    public function updateLevel($id, $level) {
      $query = "UPDATE sessions SET level = ? WHERE id = ?";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(1, $level);
      $stmt->bindParam(2, $id);

      return $stmt->execute();
    }

    public function updateActivity($id, $act) {
      $query = "UPDATE sessions SET level = :level WHERE id = :id";
      $stmt = $this->db->prepare($query);
      
      $stmt->bindParam(1, $level);
      $stmt->bindParam(2, $id);

      return $stmt->execute();
    }

    public function version () {
      return $this->db->querySingle('SELECT SQLITE_VERSION()');
    }
  }

  