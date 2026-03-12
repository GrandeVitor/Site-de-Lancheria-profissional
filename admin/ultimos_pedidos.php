<?php
session_start();
require '../config/db.php';

$ultimosPedidos = $pdo->query("SELECT * FROM pedidos ORDER BY data_pedido DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($ultimosPedidos);
?>
