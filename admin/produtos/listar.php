<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

// Mensagens de feedback
$sucesso = $_GET['sucesso'] ?? null;
$erro = $_GET['erro'] ?? null;

// Busca produtos
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY nome")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Produtos - Painel Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-primary {
            background: #4CAF50;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.9em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .produto-img {
            max-width: 80px;
            max-height: 60px;
            border-radius: 4px;
        }
        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .acoes {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lista de Produtos</h1>
        <div>
            <a href="../painel.php" class="btn btn-secondary">← Voltar ao Painel</a>
            <a href="cadastrar.php" class="btn btn-primary">+ Novo Produto</a>
        </div>
    </div>

    <?php if ($sucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $prod): ?>
            <tr>
                <td><?= $prod['id'] ?></td>
                <td>
                    <img src="../../<?= htmlspecialchars($prod['imagem']) ?>" 
                         class="produto-img"
                         alt="<?= htmlspecialchars($prod['nome']) ?>"
                         onerror="this.src='../../img/padrao/produto-sem-imagem.webp'">
                </td>
                <td><?= htmlspecialchars($prod['nome']) ?></td>
                <td>R$ <?= number_format($prod['preco'], 2, ',', '.') ?></td>
                <td><?= ucfirst(htmlspecialchars($prod['categoria'])) ?></td>
                <td class="acoes">
                    <a href="editar.php?id=<?= $prod['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                    <a href="excluir.php?id=<?= $prod['id'] ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                       Excluir
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($produtos)): ?>
        <p style="text-align: center; margin-top: 20px;">Nenhum produto cadastrado ainda.</p>
    <?php endif; ?>
</body>
</html>