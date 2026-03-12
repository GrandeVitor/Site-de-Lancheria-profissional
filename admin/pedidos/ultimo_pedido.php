<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    exit;
}

// Busca o último pedido cadastrado
$stmt = $pdo->query("SELECT id FROM pedidos ORDER BY id DESC LIMIT 1");
$ultimoPedido = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($ultimoPedido ?: ['id' => 0]);