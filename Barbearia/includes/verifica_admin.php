<?php
session_start();

if (
  !isset($_SESSION['user']) ||
  $_SESSION['user']['perfil'] !== 'admin'
) {
  header('Location: ../index.php');
  exit();
}
?>
