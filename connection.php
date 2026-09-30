<?php
try {
    $conn = new PDO("mysql:host=localhost;port=3310;dbname=partypal_catering_sytem", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
  } catch(PDOException $e) {
   // echo "Connection failed: " . $e->getMessage();
  }
  ?>