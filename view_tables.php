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

function getUserTables($mysqli, $usuario_id)
{
  $sql = "SELECT DISTINCT table_name FROM dados_usuarios WHERE usuario_id = ?";
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

$mysqli = getDbConnection();
$usuario_id = $_SESSION['id'];
$tables = getUserTables($mysqli, $usuario_id);
$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Visualizar Tabelas</title>
  <style>
    .table-container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .table-wrapper {
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>
  <h2>Visualizar Dados de Tabelas</h2>
  <div class="table-container">
    <?php
    foreach ($tables as $table_name) {
      echo "<div class='table-wrapper'>";
      echo "<h3>Tabela: " . htmlspecialchars($table_name) . "</h3>";

      $mysqli = getDbConnection();
      $sql = "SELECT coluna, valor FROM dados_usuarios WHERE usuario_id = ? AND table_name = ?";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("is", $usuario_id, $table_name);
      $stmt->execute();
      $result = $stmt->get_result();

      $data = [];
      while ($row = $result->fetch_assoc()) {
        $data[$row['coluna']][] = $row['valor'];
      }
      $stmt->close();
      $mysqli->close();

      if (!empty($data)) {
        echo "<table>";
        echo "<thead><tr>";
        foreach (array_keys($data) as $column) {
          echo "<th>" . htmlspecialchars($column) . "</th>";
        }
        echo "</tr></thead><tbody>";

        $numRows = max(array_map('count', $data));
        for ($i = 0; $i < $numRows; $i++) {
          echo "<tr>";
          foreach ($data as $column => $values) {
            echo "<td>" . htmlspecialchars($values[$i] ?? '') . "</td>";
          }
          echo "</tr>";
        }
        echo "</tbody></table>";
      } else {
        echo "<p>Nenhum dado encontrado para esta tabela.</p>";
      }
      echo "</div>";
    }
    ?>
  </div>
</body>

</html>