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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['table_name'])) {
    $table_name = $_POST['table_name'];

    $sql = "SELECT id, coluna, valor FROM dados_usuarios WHERE usuario_id = ? AND table_name = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("is", $usuario_id, $table_name);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
} else {
    $data = [];
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Excluir Dados</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            overflow: hidden;
            background-color: #333;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar a.active {
            background-color: #4caf50;
            color: #fff;
        }

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
    <div class="navbar">
        <a href="painel.php">Painel</a>
        <a href="create_table.php">Criar Tabelas</a>
        <a href="view_tables.php">Visualizar Tabelas</a>
        <a href="insert_data.php">Preencher Tabelas</a>
        <a class="active" href="delete_data.php">Excluir Dados</a>
    </div>
    <h2>Excluir Dados de Tabelas</h2>
    <form method="post" action="">
        <label for="table_name">Escolha a Tabela:</label><br>
        <select id="table_name" name="table_name" required onchange="this.form.submit()">
            <option value="">Selecione uma tabela</option>
            <?php foreach ($tables as $table_name): ?>
                <option value="<?php echo htmlspecialchars($table_name); ?>" <?php if (isset($_POST['table_name']) && $table_name == $_POST['table_name'])
                       echo 'selected'; ?>><?php echo htmlspecialchars($table_name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (!empty($data)): ?>
        <h3>Dados da tabela: <?php echo htmlspecialchars($table_name); ?></h3>
        <table>
            <thead>
                <tr>
                    <?php if (!empty($data)): ?>
                        <?php foreach (array_keys($data[0]) as $column): ?>
                            <th><?php echo htmlspecialchars($column); ?></th>
                        <?php endforeach; ?>
                        <th>Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                            <td><?php echo htmlspecialchars($cell); ?></td>
                        <?php endforeach; ?>
                        <td>
                            <form method="post" action="delete_entry.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="table_name" value="<?php echo htmlspecialchars($table_name); ?>">
                                <button type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum dado encontrado para esta tabela.</p>
    <?php endif; ?>
</body>

</html>