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

// Você só precisa instanciar a class PDOEasy uma vez por página
// Você pode informar entre () no formato de array as configurações de conexão com o banco de dados ['db' => 'my_database']

/**
 * Inseri dados na tabela works
 */

$conn->params = ['name' => 'Web Developer'];
$conn->insert('works');
var_dump($conn->query); // Ver como esta ficando a string de consulta
// var_dump($conn->exec());

/**
 * Inseri dados na tabela users
 */
$conn->params = [
    'name' => 'Jose',
    'email' => 'jose@email.com',
    'birthday' => '1988-10-23',
    'work_id' => 1
];
$conn->insert('users');
// var_dump($conn->exec());
