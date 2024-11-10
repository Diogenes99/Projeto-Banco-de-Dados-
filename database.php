<?php
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
