<?php
include("database.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<p>
<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
  <h2>Bem vindo a Informe-DATA</h2>
  nome de usuário:<br>
  <input type="text" name="username"><br>
  senha:<br>
  <input type="password" name="password"><br>
  <input type="submit" name="submit" value="enviar">
</form>
<p>Já possúi uma conta? <a href="login.php"> entre</a> </p>
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