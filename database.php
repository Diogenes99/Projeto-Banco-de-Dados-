<?php
/* $db_server = "localhost";
$db_user = "root";
$db_pass = "projeto123";
$db_name = "projetodb";
$conn = "";


try {

  $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
} catch (mysqli_sql_exception) {
  echo "não foi possivel conectar <br>";

} */

$servername = "localhost";
$username = "root";
$password = "projeto123";
$dbname = "projetodb";
// Criar conexão 
$mysqli = new mysqli($servername, $username, $password, $dbname);
// Verificar conexão 
if ($mysqli->connect_error) {
  die("Falha na conexão: " . $mysqli->connect_error);
}
?>