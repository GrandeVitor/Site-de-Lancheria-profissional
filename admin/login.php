<?php
session_start();
require '../config/db.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: painel.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($_POST['password'], $usuario['password'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        header('Location: painel.php');
        exit;
    } else {
        $erro = "Usuário ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        /* Mesmo estilo do cadastro para consistência */
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; }
        form { display: grid; gap: 15px; }
        input { padding: 10px; }
        button { background: #4CAF50; color: white; border: none; padding: 10px; cursor: pointer; }
        .erro { color: red; }
        .sucesso { color: green; }
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php 
    if (isset($erro)) echo "<p class='erro'>$erro</p>"; 
    if (isset($_GET['sucesso'])) echo "<p class='sucesso'>$_GET[sucesso]</p>"; 
    ?>
    
    <form method="post">
        <input type="text" name="username" placeholder="Nome de usuário" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    
    <p>Primeiro acesso? <a href="cadastrar-usuario.php">Cadastre-se</a></p>
</body>
</html>