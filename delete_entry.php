<?php
include("protect.php");
include("database.php");

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
  $id = $_POST['id'];
  $usuario_id = $_SESSION['id'];

  $sql = "DELETE FROM dados_usuarios WHERE id = ? AND usuario_id = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("ii", $id, $usuario_id);

  if ($stmt->execute()) {
    echo "Dados excluídos com sucesso!";
  } else {
    echo "Erro ao excluir os dados: " . $stmt->error;
  }

  $stmt->close();
  $mysqli->close();

  // Redirecionar de volta para a página de exclusão para refletir as alterações
  header("Location: delete_data.php");
  exit();
}
?>