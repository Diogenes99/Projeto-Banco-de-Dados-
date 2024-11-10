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
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      height: 100vh;
      margin: 0;
    }

    .logo {
      margin-bottom: 20px;
    }

    .login {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 300px;
      text-align: center;
    }

    .login h2 {
      margin-top: 0;
      color: #333;
    }

    .login p {
      margin: 15px 0;
    }

    .login label {
      display: block;
      margin-bottom: 5px;
      color: #555;
    }

    .login input[type="text"],
    .login input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: border-box;
    }

    .login button {
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    .login button:hover {
      background-color: #45a049;
    }

    .login a {
      color: #4CAF50;
      text-decoration: none;
    }

    .login a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <img src="logo.png" alt="" class="logo">
  <div class="login">

    <form action="" method="POST">
      <h2>Login</h2>
      <p>
        <label for="usuario">usuario</label>
        <input type="text" name="usuario">
      </p>
      <p>
        <label for="senha">senha</label>
        <input type="password" name="senha">
      </p>
      <p>
        <button type="sumbit" value="entrar">Entrar</button>
      </p>
      <p>
        Não possui uma conta? <a href="index.php">Registre-se</a>
      </p>
    </form>
  </div>

</body>

</html>
