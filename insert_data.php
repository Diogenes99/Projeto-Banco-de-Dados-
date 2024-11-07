<?php
include("protect.php");
?>
<?php
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

// Função para obter as tabelas
function getTables($mysqli)
{
  $tables = array();
  $sql = "SHOW TABLES";
  $result = $mysqli->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
      $tables[] = $row[0];
    }
  }

  return $tables;
}

// Função para obter colunas de uma tabela
function getTableColumns($mysqli, $table_name)
{
  $columns = array();
  $sql = "SHOW COLUMNS FROM $table_name";
  $result = $mysqli->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $columns[] = $row['Field'];
    }
  }

  return $columns;
}

function getUserTables($mysqli, $usuario_id)
{
  $sql = "SELECT table_name, columns FROM tabelas WHERE usuario_id = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("i", $usuario_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $tables = [];
  while ($row = $result->fetch_assoc()) {
    $tables[$row['table_name']] = explode(',', $row['columns']);
  }

  $stmt->close();
  return $tables;
}

$mysqli = getDbConnection();
$usuario_id = $_SESSION['id'];
$tables = getUserTables($mysqli, $usuario_id);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['table_name'])) {
  $table_name = $_POST['table_name'];
  $columns = $tables[$table_name];
} else {
  $columns = [];
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Inserir Dados</title>
</head>

<body>
  <h2>Inserir Dados em uma Tabela</h2>
  <form method="post" action="">
    <label for="table_name">Escolha a Tabela:</label><br>
    <select id="table_name" name="table_name" required onchange="this.form.submit()">
      <option value="">Selecione uma tabela</option>
      <?php foreach ($tables as $table_name => $columns): ?>
        <option value="<?php echo htmlspecialchars($table_name); ?>" <?php if (isset($_POST['table_name']) && $table_name == $_POST['table_name'])
             echo 'selected'; ?>><?php echo htmlspecialchars($table_name); ?></option>
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