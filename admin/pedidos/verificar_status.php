<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    exit;
}

$ids = explode(',', $_GET['ids'] ?? '');
if (empty($ids)) {
    echo json_encode([]);
    exit;
}

// Cria uma string de placeholders (?, ?, ?...)
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("SELECT id, status FROM pedidos WHERE id IN ($placeholders)");
$stmt->execute($ids);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($resultados);