<?php
include("protect.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel</title>
</head>

<body>
  <?php echo "Bem vindo ao painel, " . $_SESSION["usuario"];
  ?>
  <br>
  <a href="create_table.php">Criar Tabela</a><br>
  <a href="view_tables.php">Visualizar Tabelas</a><br>
  <a href="insert_data.php">Inserir Dados</a>
  <br>
  <a href="logout.php">Sair</a>
</body>

</html>