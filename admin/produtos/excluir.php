<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
if (isset($_GET['id'])) {
    $pdo->prepare("DELETE FROM produtos WHERE id = ?")->execute([$_GET['id']]);
}

header('Location: listar.php');
exit;
?>