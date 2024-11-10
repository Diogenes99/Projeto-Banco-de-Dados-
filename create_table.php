<?php
include("protect.php");
include("database.php");
?>
<!DOCTYPE html>
<html>

<head>
  <title>Criar Tabela</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="navbar">
    <a href="painel.php">Painel</a>
    <a class="active" href="create_table.php">Criar Tabelas</a>
    <a href="view_tables.php">Visualizar Tabelas</a>
    <a href="insert_data.php">Preencher Tabelas</a>
    <a href="delete_data.php">Excluir Dados</a>
  </div>
  <h2>Criar Nova Tabela</h2>
  <form method="post" action="create_table.php">
    <label for="table_name">Nome da Tabela:</label><br>
    <input type="text" id="table_name" name="table_name" required><br><br>
    <label for="columns">Colunas (Ex.: nome, idade, email):</label><br>
    <input type="text" id="columns" name="columns" required><br><br>
    <input type="submit" value="Criar Tabela">
  </form>
</body>

</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $table_name = $_POST['table_name'];
  $columns = $_POST['columns'];
  $usuario_id = $_SESSION['id'];

  // Sanitização básica
  $table_name = preg_replace("/[^a-zA-Z0-9_]/", "", $table_name);
  $columns_sanitized = array_map('trim', explode(',', $columns));
  $columns = implode(',', $columns_sanitized);

  $sql = "INSERT INTO tabelas (usuario_id, table_name, columns) VALUES (?, ?, ?)";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("iss", $usuario_id, $table_name, $columns);

  if ($stmt->execute()) {
    echo "Tabela '$table_name' criada com sucesso!";
  } else {
    echo "Erro ao criar a tabela: " . $stmt->error;
  }

  $stmt->close();
  $mysqli->close();
}
?>
