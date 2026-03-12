<?php
require '../config/db.php';

// Retorna total geral e total pedidos hoje em JSON
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$pedidosHoje = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE DATE(data_pedido) = CURDATE()")->fetchColumn();

echo json_encode([
  'totalPedidos' => (int)$totalPedidos,
  'pedidosHoje' => (int)$pedidosHoje
]);
?>
