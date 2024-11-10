<?php
include("protect.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="navbar">
    <a class="active" href="painel.php">Painel</a>
    <a href="create_table.php">Criar Tabelas</a>
    <a href="view_tables.php">Visualizar Tabelas</a>
    <a href="insert_data.php">Preencher Tabelas</a>
    <a href="delete_data.php">Excluir Dados</a>
  </div>
  <?php echo "<h3>Bem vindo ao painel, " . $_SESSION["usuario"] . "</h3>";
  ?>
  <br>
  <a href="create_table.php">Criar Tabela</a><br>
  <a href="view_tables.php">Visualizar Tabelas</a><br>
  <a href="insert_data.php">Inserir Dados</a><br>
  <a href="delete_data.php">Excluir Dados</a><br>
  <a href="logout.php">Sair</a>
</body>

</html>
