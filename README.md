# PDOEasy

PDOEasy é uma classe PHP para realizar ações básicas em um banco de dados usando SQL de forma simplificada. A PDOEasy trata e valida todas as informações antes de cada ação na base de dados. A classe é uma abstração da extensão PDO do PHP que é reconhecida por ser muito segura e de fácil implementação.

## Configurações

Defina acima da instancia a seguinte constante com as configurações da base de dados que você quer conectar.

```php

define('DATA_BASE', [
    'host' => 'localhost',
    'db' => 'pdoeasy',
    'port' => '3306',
    'user' => 'root',
    'psw' => '',
]);

```

Seguindo uma boa pratica, você pode criar estas informações em arquivo de configuração do seu projeto.

### Lembre-se de baixar e incluir o arquivo 'PDOEasy.class.php' em seu projeto

## Como usar a PDOEasy

Você pode instanciar a PDOEasy apenas uma vez a cada arquivo para fazer um bom uso da memória.

```php

use Models\Database\PDOEasy;

$conn = new PDOEasy();

```

Caso precise se conectar a um banco de dados diferente basta passar as informações da conexão na instância do objeto dessa forma:

```php

$conn = new PDOEasy([
    'db' => 'db_alternative',
    'port' => '3306',
    'user' => 'my_user',
    'psw' => 'my_password',
]);

```

Um exemplo simples de SELECT para obter todos os dados da tabela "users"

```php

$conn->select('users');
$conn->exec();
$result = $conn->fetchAll();

```

#### CRUD

Os principais métodos da **PDOEasy** são (**insert**, **select**, **update** e **delete**). Cada vez que um destes métodos é chamado inicia-se um novo comando **SQL**.


### Comando SQL.

O que a **PDOEasy** faz, é facilitar a escrita do comando **SQL**. Você pode consultar a variável pública **$query** para verificar como o comando **SQL** está sendo escrito. Você também pode passar o comandos direto na variável **$query** caso precisa fazer algum comando mirabolante.

```php

$conn->params = ['name' => 'Web Developer'];
$conn->insert('works');
var_dump($conn->query);

$conn->exec() // Executa o comando

// INSERT INTO works (name) values (:name)

```

Passando o comando **SQL** na variável **$query**

```php

$conn->params = ['name' => 'Web Developer'];
$conn->query = "INSERT INTO works (name) values (:name)";
$conn->exec() // Executa o comando

```

Mas, usar assim não faz sentido não é mesmo? Então Ignoremos isto! kkkk

O método **exec()** Executa o comando criando na variável **$query** e retorna verdadeiro ou falso para a ação.

O método **fetchAll()** retorna os dados de um **select**

O método **rowCount()** retorna a quantidade de linhas afetadas pelo comando **SQL**

## Importante

Os métodos da classe deve ser passados na mesma ordem em que você escreveria um comando **SQL**. Exemplo:

##### Imagine o comando SQL

```sql

SELECT u.name nome, w.name w_name FROM users u
INNER JOIN works w on w.id = u.work_id
WHERE u.work_id = 1 ORDER BY u.name ASC LIMIT 3;

```

##### Com a PDOEasy você faz assim

```php

$conn->params = ['work_id' => 1]
$conn->select('users u', 'u.name nome, w.name w_name');
$conn->join('works w', 'w.id', 'u.work_id');
$conn->where('u.work_id = :work_id');
$conn->order('u.name ASC');
$comm->limit(3)
$conn->exec();

$result = $conn->fetchAll();

```

## Parâmetros seguros

Como a **PDOEasy** é uma abstração da extensão **PDO**, Os parâmetros passados na query string devem ser seguros com palavras chaves utilizando dois pontos **:param**. Outro requisito da classe é que você deve passar os valores dos parâmetros no formato de array na variável **->params** e acima de todos os outro métodos, como no exemplo anterior.


## Exemplo de INSERT

INSERT dados na tabela users

```php

$conn->params = [
    'name' => 'Jose',
    'email' => 'jose@email.com',
    'work_id' => 1
];
$conn->insert('users');
$conn->exec();

```

## Exemplos de SELECT

Obter o nome e o email dos usuário

```php

$conn->select('users', 'name, email');
$conn->exec();
$result = $conn->fetchAll();

```

Obter o nome do usuário onde o id = 1

```php

$conn->select('users', 'name');
$conn->where('id = :id');
$conn->exec();
$user_name = $conn->fetchAll()[0]['name'] ?? null;

```

Adicionar um LIMIT o ordenar os dados

```php

$conn->select('users');
$conn->order('name ASC');
$conn->limit(2);
$result = $conn->fetchAll();

```

SELECT com INNER JOIN

```php

$conn->select('users u', 'u.name name, w.name w_name');
$conn->join('works w', 'w.id', 'u.work_id', 'INNER JOIN');
$conn->exec();
$result = $conn->fetchAll();

```

SELECT com LIMIT e OFFSET utilizando _SQL_CALC_FOUND_ROWS_ para fazer paginação

```php

$conn->select('users', '*', true);
$conn->limit(3, 0);
$conn->exec();
$result = $conn->fetchAll();

```

Repete a query anterior sem o parâmetro LIMIT

```php

$total_users = $conn->foundRows();

```

## Exemplo de UPDATE

Atualiza o nome e o email do usuário onde id = 1

```php

$conn->params = [
    'name' => 'Maria da Silva',
    'email' => 'mariadasilva@email.com',
    'id' => 1,
];
$conn->update('users', 'name = :name, email = :email');
$conn->where('id = :id');
$conn->limit(1);
$conn->exec(); // Retorna true ou false

```

## Exemplo de DELETE

DELETE simples na tabela users onde o usuário tem id = 1

```php

$conn->params = ['id' => 1];
$conn->delete('users');
$conn->where('id = :id');
$conn->limit(1);
$conn->exec();

```

### Nos comandos (UPDATE e DELETE) os métodos (WHERE() e LIMIT()) são obrigatórios.

## Exception

Use o método **debug** para debugar a classe

## Author

- [Jeterson Lordano](https://github.com/jetersonlordano)
- [Website](https://www.jetersonlordano.com.br)
