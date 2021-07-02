<?php

/**
 * Simple SELECT PDO
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

try {
  
    $pdo = new PDO('mysql:host=localhost;dbname=pdoeasy', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query String
    $stmt = $pdo->prepare('SELECT * FROM works WHERE name = :name');

    $work = 'web developer';
    $stmt->bindParam(':name', $work, PDO::PARAM_STR);

    $stmt->execute();
    
    var_dump($stmt->fetchAll());

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
