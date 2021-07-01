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
 * Obter todos os dados da tabela users
 */

$conn->select('users');
//$conn->exec();
//$result = $conn->fetchAll();
//var_dump($result);

/**
 * Obter o nome e o email dos usuário
 */
$conn->select('users', 'name, email');
// $conn->exec();
// $result = $conn->fetchAll()[0] ?? [];
// var_dump($result);

/**
 * Obter o nome do usuário onde o id = 1
 */
$conn->params = ['id' => 1];
$conn->select('users', 'name');
$conn->where('id = :id');
// $conn->exec();
// $user_name = $conn->fetchAll()[0]['name'] ?? null;
// var_dump($user_name);

/**
 * Adicionar um limite o ordenar os dados
 */
$conn->select('works', 'name');
$conn->order('name ASC');
$conn->limit(2);
// $conn->exec();
// $result = $conn->fetchAll();
// var_dump($result);

/**
 * Select com Join
 * INNER JOIN é padrão
 */
$conn->select('users u', 'u.name name, w.name w_name');
$conn->join('works w', 'w.id', 'u.work_id');
// $conn->exec();
// $result = $conn->fetchAll() ?? [];
// var_dump($result);

/**
 * Select com Limit e Offset
 * SQL_CALC_FOUND_ROWS para fazer paginação
 */
$limit = 2;
$offset = 0;
$conn->select('works', '*', true);
$conn->limit($limit, $offset);
// $conn->exec();
// $result = $conn->fetchAll();
// var_dump($result);

/**
 * Repete a query anterior sem o parâmetro LIMIT
 */
// $total_users = $conn->foundRows();
// var_dump($total_users);
