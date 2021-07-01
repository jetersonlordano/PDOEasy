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
 * Atualiza o nome e o email do usuário com id = 1
 */
$conn->params = [
    'name' => 'Maria da Silva',
    'email' => 'mariadasilva@email.com',
    'id' => 1,
];
$conn->update('users', 'name = :name, email = :email');
$conn->where('id = :id');
$conn->limit();
var_dump($conn->exec());  // Retorna true ou false
