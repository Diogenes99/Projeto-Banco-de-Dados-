<?php
include("database.php");

if (isset($_POST["usuario"]) || isset($_POST["senha"])) {
  if (strlen($_POST["usuario"]) == 0) {
    echo "Preencha seu nome de usuário";
  } elseif (strlen($_POST["senha"]) == 0) {
    echo "Preencha sua senha";
  } else {
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    $sql_code = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

    $quantidade = $sql_query->num_rows;

    if ($quantidade == 1) {
      $usuario_data = $sql_query->fetch_assoc();

      if (password_verify($senha, $usuario_data['senha'])) {
        if (!isset($_SESSION)) {
          session_start();
        }

        $_SESSION["id"] = $usuario_data["id"];
        $_SESSION["usuario"] = $usuario_data["usuario"];
        $_SESSION["senha"] = $usuario_data["senha"];

        header("Location: painel.php");
      } else {
        echo "Falha ao logar! Usuário ou senha incorretos.";
      }
    } else {
      echo "Falha ao logar! Usuário ou senha incorretos.";
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>

<body>
  <form action="" method="POST">
    <h2>Login</h2>
    <p>
      <label for="usuario">usuario</label>
      <input type="text" name="usuario">
    </p>
    <p>
      <label for="senha">Senha</label>
      <input type="password" name="senha">
    </p>
    <p>
      <button type="sumbit" value="entrar">Entrar</button>
    </p>
    <p>
      Não possui uma conta? <a href="index.php">Registre-se</a>
    </p>
  </form>
</body>

</html>