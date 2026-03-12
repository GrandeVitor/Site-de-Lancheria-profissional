<?php
session_start();
require '../config/db.php';

// Se já estiver logado, redireciona
if (isset($_SESSION['usuario_id'])) {
    header('Location: painel.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $senha = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nome = $_POST['nome'];

    try {
        $pdo->prepare("INSERT INTO usuarios (username, password, nome) VALUES (?, ?, ?)")
            ->execute([$username, $senha, $nome]);
        
        header('Location: login.php?sucesso=Usuário cadastrado! Faça login.');
        exit;
    } catch (PDOException $e) {
        $erro = "Erro: " . (str_contains($e->getMessage(), 'Duplicate') ? 'Usuário já existe' : 'Dados inválidos');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Usuário</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; }
        form { display: grid; gap: 15px; }
        input { padding: 10px; }
        button { background: #4CAF50; color: white; border: none; padding: 10px; cursor: pointer; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Cadastrar Usuário</h1>
    <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
    
    <form method="post">
        <input type="text" name="username" placeholder="Nome de usuário" required>
        <input type="password" name="password" placeholder="Senha" required>
        <input type="text" name="nome" placeholder="Seu nome completo" required>
        <button type="submit">Cadastrar</button>
    </form>
    
    <p>Já tem conta? <a href="login.php">Faça login</a></p>
</body>
</html>