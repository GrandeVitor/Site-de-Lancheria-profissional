<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['id']]);
}

// Redireciona de volta para a página anterior
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'listar.php'));
exit;