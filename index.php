<?php
include("database.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .registrar {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 300px;
    }

    .registrar h2 {
      margin-top: 0;
      text-align: center;
      color: #333;
    }

    .registrar p {
      margin: 15px 0;
    }

    .registrar label {
      display: block;
      margin-bottom: 5px;
      color: #555;
    }

    .registrar input[type="text"],
    .registrar input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: border-box;
    }

    .registrar input[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    .registrar input[type="submit"]:hover {
      background-color: #45a049;
    }

    .registrar a {
      color: #4CAF50;
      text-decoration: none;
    }

    .registrar a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">

  <div class="registrar">
    <h2>Registre-se</h2>
    <p>
      <label for="usuario">nome de usuário</label>
      <input type="text" name="username">
    </p>
    <p>
      <label for="senha">senha</label>
      <input type="password" name="password"><br>
    </p>
    <input type="submit" name="submit" value="enviar">

</form>
Já possúi uma conta? <a href="login.php"> entre</a>
</div>

</body>

</html>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

  if (empty($username)) {
    echo "Preencha o nome de usuário";
  } elseif (empty($password)) {
    echo "Preencha a senha";
  } else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (usuario, senha)
      VALUES ('$username', '$hash')";
    try {
      mysqli_query($mysqli, $sql);
      echo "Você esta registrado!";
    } catch (mysqli_sql_exception) {
      echo "Este nome de usuário já está em uso";
    }

  }
}

mysqli_close($mysqli);
?>
