<?php
include("database.php");
include("protect.php");

function getDbConnection()
{
  $servername = "localhost";
  $username = "root";
  $password = "projeto123";
  $dbname = "projetodb";

  // Criar conexão
  $mysqli = new mysqli($servername, $username, $password, $dbname);

  // Verificar conexão
  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }

  return $mysqli;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $table_name = $_POST['table_name'];
  $usuario_id = $_SESSION['id'];
  $columns = array_keys($_POST);
  $values = array_values($_POST);

  // Remover o nome da tabela do array de colunas
  array_shift($columns);
  array_shift($values);

  $mysqli = getDbConnection();

  for ($i = 0; $i < count($columns); $i++) {
    $coluna = $columns[$i];
    $valor = $mysqli->real_escape_string($values[$i]);

    $sql = "INSERT INTO dados_usuarios (usuario_id, table_name, coluna, valor) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("isss", $usuario_id, $table_name, $coluna, $valor);

    if (!$stmt->execute()) {
      echo "Erro ao inserir os dados: " . $stmt->error;
    }

    $stmt->close();
  }

  echo "Dados inseridos com sucesso na tabela '$table_name'!";
  $mysqli->close();
}
?>