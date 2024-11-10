<?php
include("protect.php");
include("database.php");

// Definir a função de conexão aqui mesmo
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

function getUserTables($mysqli, $usuario_id)
{
  $sql = "SELECT DISTINCT table_name FROM tabelas WHERE usuario_id = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("i", $usuario_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $tables = [];
  while ($row = $result->fetch_assoc()) {
    $tables[] = $row['table_name'];
  }

  $stmt->close();
  return $tables;
}

function getTableColumns($mysqli, $table_name)
{
  $sql = "SELECT columns FROM tabelas WHERE table_name = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("s", $table_name);
  $stmt->execute();
  $result = $stmt->get_result();

  $columns = [];
  if ($row = $result->fetch_assoc()) {
    $columns = explode(',', $row['columns']);
  }

  $stmt->close();
  return $columns;
}

$mysqli = getDbConnection();
$usuario_id = $_SESSION['id'];
$tables = getUserTables($mysqli, $usuario_id);

$table_name = '';
$columns = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['table_name'])) {
  $table_name = $_POST['table_name'];
  $columns = getTableColumns($mysqli, $table_name);
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Inserir Dados</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="navbar">
    <a href="painel.php">Painel</a>
    <a href="create_table.php">Criar Tabelas</a>
    <a href="view_tables.php">Visualizar Tabelas</a>
    <a class="active" href="insert_data.php">Preencher Tabelas</a>
    <a href="delete_data.php">Excluir Dados</a>
  </div>
  <h2>Inserir Dados em uma Tabela</h2>
  <form method="post" action="">
    <label for="table_name">Escolha a Tabela:</label><br>
    <select id="table_name" name="table_name" required onchange="this.form.submit()">
      <option value="">Selecione uma tabela</option>
      <?php foreach ($tables as $table): ?>
        <option value="<?php echo htmlspecialchars($table); ?>" <?php if ($table == $table_name)
             echo 'selected'; ?>>
          <?php echo htmlspecialchars($table); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>

  <?php if (!empty($columns)): ?>
    <form method="post" action="save_data.php">
      <input type="hidden" name="table_name" value="<?php echo htmlspecialchars($table_name); ?>">
      <?php foreach ($columns as $column): ?>
        <label for="<?php echo $column; ?>"><?php echo $column; ?>:</label><br>
        <input type="text" id="<?php echo $column; ?>" name="<?php echo $column; ?>" required><br><br>
      <?php endforeach; ?>
      <input type="submit" value="Inserir Dados">
    </form>
  <?php endif; ?>
</body>

</html>
