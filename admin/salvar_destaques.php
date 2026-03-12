<?php
session_start();
require '../config/db.php';

// Verificação de login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verifica se é uma submissão POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: destaques.php');
    exit;
}

// Inicia transação para garantir integridade
$pdo->beginTransaction();

try {
    // Primeiro desmarca TODOS os produtos
    $pdo->exec("UPDATE produtos SET destaque = 0");
    
    // Se houver produtos selecionados, marca apenas esses
    if (!empty($_POST['destaques'])) {
        // Filtra e valida os IDs
        $ids = array_filter($_POST['destaques'], 'is_numeric');
        $ids = array_map('intval', $ids);
        
        // Previne SQL injection usando prepared statements
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "UPDATE produtos SET destaque = 1 WHERE id IN ($placeholders)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);
    }
    
    $pdo->commit();
    header('Location: destaques.php?success=1');
    
} catch (Exception $e) {
    $pdo->rollBack();
    header('Location: destaques.php?error=1');
}

exit;