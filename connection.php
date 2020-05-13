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
          "level_5_input" TEXT,
          "level_6_input" TEXT,
          "level_7_input" TEXT,
          "level_8_input" TEXT
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

    public function updateActivity($act) {
      $query = "UPDATE sessions 
        SET 
          level = :level, 
          level_1_input = :level_1_input,  
          level_2_input = :level_2_input, 
          level_3_input = :level_3_input, 
          level_4_input = :level_4_input, 
          level_5_input = :level_5_input, 
          status = :status
        WHERE id = :id
      ";
      
      $stmt = $this->db->prepare($query);

      $stmt->bindValue(':level', $act['level'], SQLITE3_INTEGER);
      $stmt->bindValue(':level_1_input', $act['level_1_input'], SQLITE3_TEXT);
      $stmt->bindValue(':level_2_input', $act['level_2_input'], SQLITE3_TEXT);
      $stmt->bindValue(':level_3_input', $act['level_3_input'], SQLITE3_TEXT);
      $stmt->bindValue(':level_4_input', $act['level_4_input'], SQLITE3_TEXT);
      $stmt->bindValue(':level_5_input', $act['level_5_input'], SQLITE3_TEXT);
      $stmt->bindValue(':status', $act['status'], SQLITE3_TEXT);
      $stmt->bindValue(':id', $act['id'], SQLITE3_INTEGER);

      return $stmt->execute();
    }

    public function version () {
      return $this->db->querySingle('SELECT SQLITE_VERSION()');
    }
  }

  