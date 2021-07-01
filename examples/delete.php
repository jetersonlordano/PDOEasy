<?php

/**
 * SQL Select com PDOEasy
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Config.inc.php';

use Models\Database\PDOEasy;

/**
 * Instância da classe PDOEasy
 */

$conn = new PDOEasy();

/**
 * Instância da classe PDOEasy
 */

$conn->params = ['id' => 1];
$conn->delete('users');
$conn->where('id = :id');
$conn->limit(1);
var_dump($conn->query);
var_dump($conn->exec());