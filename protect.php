<?php
session_start();
if (!isset($_SESSION["usuario"])) {
  header("Location: login.php");
  exit();
}
if (!isset($_SESSION["id"])) {
  die("Entre para acessar o painel. <p><a href=\"login.php\">Entrar</a></p>");
}
?>